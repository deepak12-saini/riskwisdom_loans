# Calendly webhook → Admin enquiries

When someone books on `/book`, Calendly emails them and adds the event to your calendar.
With this webhook, the same booking is also saved as an **Enquiry** in `/admin/enquiries`
(lead type: **Calendly booking**) so staff can call from the admin panel.

## Requirements

- Calendly **paid** plan that supports webhook subscriptions
- Personal Access Token from [Calendly Integrations → API & webhooks](https://calendly.com/integrations/api_webhooks)
- Live site HTTPS URL (example: `https://riskwisdomloans.com.au/webhooks/calendly`)

## 1. Add signing key to `.env` (after step 2)

```env
CALENDLY_WEBHOOK_SIGNING_KEY=paste_signing_key_here
```

Then on the server:

```bash
php artisan config:clear
php artisan config:cache
```

## 2. Create the webhook subscription (Calendly API)

Get your organization URI (or user URI):

```bash
curl --request GET \
  --url https://api.calendly.com/users/me \
  --header "Authorization: Bearer YOUR_PERSONAL_ACCESS_TOKEN"
```

Create the subscription (organization scope example):

```bash
curl --request POST \
  --url https://api.calendly.com/webhook_subscriptions \
  --header "Content-Type: application/json" \
  --header "Authorization: Bearer YOUR_PERSONAL_ACCESS_TOKEN" \
  --data '{
    "url": "https://riskwisdomloans.com.au/webhooks/calendly",
    "events": ["invitee.created", "invitee.canceled"],
    "organization": "https://api.calendly.com/organizations/YOUR_ORG_UUID",
    "scope": "organization"
  }'
```

Save the **signing_key** from the API response into `.env` as `CALENDLY_WEBHOOK_SIGNING_KEY`.

For user-only bookings, use `"scope": "user"` and pass `"user": "https://api.calendly.com/users/..."`.

## 3. Collect phone numbers in Calendly

Add a custom question on the event type, e.g. **Phone number** (required).
The webhook looks for question labels containing phone / mobile / cell.

## 4. Test

1. Book a test slot on `/book`
2. Check Admin → Enquiries → filter **Calendly**
3. Confirm name, email, phone, and booked time appear
4. Cancel the booking in Calendly — enquiry status should show canceled

## Notes

- Duplicate `invitee.created` events for the same invitee URI are ignored
- Admin also gets the usual enquiry notification email
- Calendly’s own confirmation emails still send separately
- Endpoint: `POST /webhooks/calendly` (CSRF exempt, signature verified)
