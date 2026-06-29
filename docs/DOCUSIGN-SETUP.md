# DocuSign setup — Riskwisdom Loans

DocuSign sends PDFs for digital signature and stores signed copies on each **client file** in admin.

## 1. Create DocuSign developer account

1. Go to [developers.docusign.com](https://developers.docusign.com/)
2. Create a **developer / sandbox** account (free for testing)
3. Note your **Account ID** and **User ID** (GUID)

## 2. Create integration (JWT app)

1. **Apps and Keys** → **Add App and Integration Key**
2. Copy the **Integration Key**
3. Generate an **RSA keypair** — download the private key
4. Add redirect URI (optional for JWT): `https://riskwisdomloans.com.au`
5. Under **User Application**, grant consent once:
   ```
   https://account-d.docusign.com/oauth/auth?response_type=code&scope=signature%20impersonation&client_id=YOUR_INTEGRATION_KEY&redirect_uri=https://riskwisdomloans.com.au
   ```
   Log in as the DocuSign user and click **Allow**.

## 3. Add keys to `.env`

```env
DOCUSIGN_ENABLED=true
DOCUSIGN_ENV=demo
DOCUSIGN_INTEGRATION_KEY=your-integration-key
DOCUSIGN_USER_ID=your-user-guid
DOCUSIGN_ACCOUNT_ID=your-account-id
DOCUSIGN_PRIVATE_KEY_PATH=/path/to/docusign-private.key
DOCUSIGN_WEBHOOK_SECRET=optional-hmac-secret
```

Or paste the private key inline (escape newlines as `\n`):

```env
DOCUSIGN_PRIVATE_KEY="-----BEGIN RSA PRIVATE KEY-----\n...\n-----END RSA PRIVATE KEY-----"
```

Then run:

```bash
php artisan config:clear
```

## 4. Webhook (production)

In DocuSign admin → **Connect** → add webhook:

- **URL:** `https://riskwisdomloans.com.au/webhooks/docusign`
- **Events:** Envelope Completed, Envelope Declined
- **Include HMAC:** optional — set same value in `DOCUSIGN_WEBHOOK_SECRET`

When a client signs, the signed PDF is downloaded and stored automatically.

## 5. Using in admin

1. Open a **client file**
2. Scroll to **DocuSign documents**
3. Upload PDF → **Send via DocuSign**
4. Client receives email → signs
5. **Download PDF** appears when signed (or click **Sync status**)

## Production

Change `DOCUSIGN_ENV=production` and use production account keys + `account.docusign.com` consent URL.
