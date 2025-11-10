# TESTING MIDTRANS INTEGRATION

## API Endpoints

### 1. Create Payment (Get Snap Token)
```bash
POST /member/payment/create
Headers:
  X-CSRF-TOKEN: {csrf_token}
  Cookie: laravel_session={session_id}

Response Success:
{
  "success": true,
  "snap_token": "66e4fa55-fdac-4ef9-91b5-733b5d859...",
  "order_id": 123
}
```

### 2. Payment Notification (Called by Midtrans)
```bash
POST /midtrans/notification
Headers:
  Content-Type: application/json

Body: (Midtrans akan mengirim data ini)
{
  "transaction_time": "2025-10-29 13:15:00",
  "transaction_status": "settlement",
  "transaction_id": "abc123",
  "status_message": "Success",
  "status_code": "200",
  "signature_key": "...",
  "payment_type": "bank_transfer",
  "order_id": "ORD20251029-ABC123",
  "merchant_id": "G123456789",
  "gross_amount": "350000.00",
  "fraud_status": "accept",
  "currency": "IDR"
}
```

### 3. Check Payment Status
```bash
GET /member/payment/status/{order_number}

Response:
{
  "order_number": "ORD20251029-ABC123",
  "payment_status": "paid",
  "status": "awaiting_preparation",
  "total": "350000.00"
}
```

### 4. Payment Finish (Redirect from Midtrans)
```bash
GET /member/payment/finish?order_id={order_number}&transaction_status={status}

Will redirect to: /member/orders/{id}
```

---

## Testing Flow

### Scenario 1: Successful Payment

1. **Member adds product to cart**
   - Navigate to catalog
   - Click "Tambah ke Keranjang"

2. **Member goes to cart**
   - Click "Keranjang" menu
   - See cart items

3. **Member initiates payment**
   - Select "Bayar Online (Midtrans)"
   - Click "Bayar Sekarang"
   
4. **Frontend calls create payment API**
   ```javascript
   fetch('/member/payment/create', {
     method: 'POST',
     headers: {
       'X-CSRF-TOKEN': csrf_token
     }
   })
   ```

5. **Backend creates order and returns snap_token**
   - Order created with status: 'pending'
   - Payment status: 'pending'
   - Snap token generated

6. **Frontend opens Midtrans Snap popup**
   ```javascript
   snap.pay(snap_token, {
     onSuccess: function(result) { ... },
     onPending: function(result) { ... },
     onError: function(result) { ... }
   })
   ```

7. **User completes payment in Snap**
   - Select payment method (Bank Transfer/E-wallet/Card)
   - Complete payment

8. **Midtrans sends notification to backend**
   - POST to /midtrans/notification
   - Backend updates order:
     - payment_status: 'paid'
     - status: 'awaiting_preparation'
     - paid_at: current timestamp
   - Stock reduced

9. **User redirected to success page**
   - Cart cleared
   - Shows order details
   - Can track order status

---

## Testing with CURL

### Test Create Payment (After login as member)
```bash
# Get CSRF token first from login page
curl -X POST http://localhost:8000/member/payment/create \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -H "Cookie: laravel_session=your-session-cookie"
```

### Test Notification (Simulate Midtrans callback)
```bash
curl -X POST http://localhost:8000/midtrans/notification \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_time": "2025-10-29 13:15:00",
    "transaction_status": "settlement",
    "transaction_id": "test-123",
    "status_code": "200",
    "signature_key": "valid-signature",
    "payment_type": "bank_transfer",
    "order_id": "ORD20251029-ABC123",
    "merchant_id": "G123456789",
    "gross_amount": "350000.00",
    "fraud_status": "accept"
  }'
```

---

## Database States

### Order Created (Before Payment)
```sql
SELECT * FROM member_orders WHERE order_number = 'ORD20251029-ABC123';

id_order: 1
order_number: ORD20251029-ABC123
payment_method: midtrans
snap_token: 66e4fa55-fdac-4ef9-91b5...
transaction_id: NULL
payment_type: NULL
payment_status: pending
paid_at: NULL
status: pending
total: 350000.00
```

### After Successful Payment
```sql
SELECT * FROM member_orders WHERE order_number = 'ORD20251029-ABC123';

id_order: 1
order_number: ORD20251029-ABC123
payment_method: midtrans
snap_token: 66e4fa55-fdac-4ef9-91b5...
transaction_id: test-123
payment_type: bank_transfer
payment_status: paid
paid_at: 2025-10-29 13:15:00
status: awaiting_preparation
total: 350000.00
```

---

## Monitoring

### Check Logs
```bash
# Watch logs in real-time
tail -f storage/logs/laravel.log

# Filter Midtrans logs
tail -f storage/logs/laravel.log | grep Midtrans
```

### Check Database
```sql
-- Recent orders
SELECT order_number, payment_status, status, total, created_at 
FROM member_orders 
ORDER BY created_at DESC 
LIMIT 10;

-- Pending payments
SELECT order_number, payment_status, total, created_at 
FROM member_orders 
WHERE payment_status = 'pending' 
AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR);

-- Failed payments
SELECT order_number, payment_status, status, total 
FROM member_orders 
WHERE payment_status = 'failed';
```

---

## Common Issues & Solutions

### Issue: Snap token not generated
**Check:**
- MIDTRANS_SERVER_KEY in .env is correct
- Product stock is available
- Cart is not empty

**Debug:**
```bash
tail -f storage/logs/laravel.log | grep "Payment creation failed"
```

### Issue: Notification not received
**Check:**
- Notification URL is set in Midtrans Dashboard
- For local: ngrok is running
- Route /midtrans/notification is accessible

**Test:**
```bash
curl -X POST http://your-domain.com/midtrans/notification \
  -H "Content-Type: application/json" \
  -d '{"test": "data"}'
```

### Issue: Payment stuck in pending
**Check:**
- Midtrans Dashboard for transaction status
- Notification URL is reachable from internet
- Check logs for notification errors

**Manual Fix:**
```sql
-- If payment is actually success but status not updated
UPDATE member_orders 
SET payment_status = 'paid', 
    status = 'awaiting_preparation',
    paid_at = NOW()
WHERE order_number = 'ORD20251029-ABC123';
```

---

## Production Checklist

- [ ] Switch to production API keys
- [ ] Update Snap script URL to production
- [ ] Set production notification URL
- [ ] Test with small amount
- [ ] Setup monitoring alerts
- [ ] Document refund process
- [ ] Train admin staff
