# Email setup — form submissions

When a visitor submits the **contact form**, **rate review**, or **borrowing power** form, the site sends **two emails**:

| Email | To | Purpose |
|-------|-----|---------|
| **Staff notification** | `info@riskwisdomloans.com.au` | New lead details for Kal / team |
| **Client auto-reply** | Visitor’s email address | “We received your enquiry” confirmation |

This is handled by `EnquiryNotificationService` — no extra code needed once SMTP works.

---

## Production `.env` (required)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.office365.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=info@riskwisdomloans.com.au
MAIL_PASSWORD=your-app-password-here
MAIL_FROM_ADDRESS="info@riskwisdomloans.com.au"
MAIL_FROM_NAME="Riskwisdom Loans"
MAIL_EHLO_DOMAIN=riskwisdomloans.com.au
CONTACT_TO_ADDRESS="info@riskwisdomloans.com.au"
```

Then on the server:

```bash
php artisan config:clear
php artisan config:cache
```

---

## Microsoft 365 (info@riskwisdomloans.com.au)

If emails **save to admin** but **do not send**, SMTP is usually blocked.

### 1. Enable SMTP AUTH for the mailbox

Microsoft 365 admin → **Users** → `info@riskwisdomloans.com.au` → **Mail** → ensure SMTP AUTH is **enabled** for this mailbox.

### 2. Use an app password (if MFA is on)

1. Sign in to [account.microsoft.com](https://account.microsoft.com) as `info@...`
2. **Security** → **App passwords** (or use Azure app registration)
3. Create app password → paste into `MAIL_PASSWORD` in `.env`

Regular account password often fails with error **535 5.7.139**.

### 3. Test from server

```bash
php artisan tinker
```

```php
Mail::raw('Test from Riskwisdom', fn ($m) => $m->to('your@gmail.com')->subject('Mail test'));
```

Check inbox and `storage/logs/laravel.log` for errors.

---

## How to know if email worked

| Sign | Meaning |
|------|---------|
| Thank-you page, no warning | Emails likely sent |
| Thank-you page **red warning** “email notification could not be sent” | SMTP failed — lead still saved in admin |
| Admin enquiry row | Always saved regardless of email |

---

## Alternative if M365 keeps failing

Use a transactional provider (often easier):

- **Mailgun**, **Postmark**, **Resend**, or **SendGrid**
- Set `MAIL_MAILER` and API keys per provider docs
- Keep `MAIL_FROM_ADDRESS=info@riskwisdomloans.com.au` and verify domain DNS

---

## Email templates

- Staff: `resources/views/emails/contact-enquiry.blade.php`
- Client: `resources/views/emails/contact-auto-reply.blade.php`
