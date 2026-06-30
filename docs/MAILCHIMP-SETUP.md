# Mailchimp setup — Riskwisdom Loans

Mailchimp handles **email marketing**: newsletters, nurture sequences, and segments for mortgage prospects and past clients.

**Website status:** Forms include an optional marketing opt-in. When enabled in `.env`, opted-in leads sync to Mailchimp automatically with tags.

---

## 1. Account and audience

1. Log in at [https://us11.admin.mailchimp.com/](https://us11.admin.mailchimp.com/) (or your datacenter URL).
2. **Audience** → **All contacts** → create audience **Riskwisdom Leads** if needed.
3. Default from email: `info@riskwisdomloans.com.au`
4. Note the **Audience ID**: Audience → Settings → Audience name and defaults → **Audience ID** (e.g. `a1b2c3d4e5`).

---

## 2. Verify domain (GoDaddy DNS)

1. Mailchimp → **Account** → **Settings** → **Domains** (or **Website** → **Domains**).
2. Add `riskwisdomloans.com.au`.
3. Copy DNS records (CNAME, TXT) into **GoDaddy** → DNS for the domain.
4. Wait 15 minutes–48 hours → **Verify** in Mailchimp.

Without this, campaigns often land in spam.

---

## 3. API key (for website sync)

1. Mailchimp → profile icon → **Account & billing** → **Extras** → **API keys**.
2. **Create a key** → copy the key (ends with `-us11` or similar).
3. **Server prefix** = the part after the hyphen (e.g. `us11`).

Add to production `.env`:

```env
MAILCHIMP_ENABLED=true
MAILCHIMP_API_KEY=your-key-us11
MAILCHIMP_SERVER_PREFIX=us11
MAILCHIMP_AUDIENCE_ID=your-audience-id
```

Then on the server:

```bash
php artisan config:clear
php artisan config:cache
php artisan migrate
```

Keep `MAILCHIMP_ENABLED=false` until the API key and audience ID are set.

---

## 4. Import Kal’s customer list (manual)

For **past clients** Kal sends by CSV:

1. **Audience** → **All contacts** → **Import contacts**.
2. Upload CSV with columns: **Email**, **First Name**, **Last Name**, **Phone** (optional).
3. Map columns → import as **Subscribed** only for people who agreed to marketing emails.
4. After import, select contacts → **Add tag** → `client`.

**Do not** import people without marketing consent (Australian Spam Act).

---

## 5. Reusable email templates

Create under **Campaigns** → **Email templates** → filter **Newsletter**:

| Template | Use |
|----------|-----|
| **Welcome** | New opt-in from website |
| **Monthly newsletter** | Rate snapshot + one broker tip |
| **Refinance tip** | Fixed rate ending, cashback offers |

Apply **Brand Kit**: Riskwisdom logo, brand colours, `info@` sender.

**Mortgage mapping:**

- Promotions → lender cashback / special offers
- Company announcements → panel or office updates
- Event invitations → first home buyer info session
- Product launches → new loan product or offset info

---

## 6. Tags (website sends these automatically)

When a visitor opts in on a form, the site tags the contact:

| Tag | When |
|-----|------|
| `website-lead` | Every synced website lead |
| `contact` | Contact form |
| `rate_review` | Rate review form |
| `borrowing_power` | Borrowing calculator |
| `refinance`, `home_purchase`, etc. | Loan type from form |
| `ready_now`, `1_3_months`, etc. | Timeline (contact form) |
| `NSW`, `VIC`, etc. | State |
| `utm-{campaign}` | Paid ad campaign name when present |
| `client` | Added when enquiry is converted to client file in admin |

Use tags for **Segments** and **Automations**.

---

## 7. First automation (nurture funnel)

1. **Automations** → **Create** → **Custom**.
2. Trigger: **Tag added** → `website-lead`.
3. Emails:

| Day | Subject idea | CTA |
|-----|--------------|-----|
| 0 | Thanks — what happens next | Book a call (Calendly) |
| 2 | Still comparing rates? | `/rate-review` |
| 5 | Borrowing calculator | `/tools/borrowing-power` |

4. Activate automation.

---

## 8. Segments (recommended)

| Segment | Rule |
|---------|------|
| Hot leads | Tag `ready_now` |
| Refinance | Tag `refinance` or `rate_review` |
| First home buyer | Tag `home_purchase` + source/intent |
| Past clients | Tag `client` |
| Paid ads | Tag starts with `utm-` |

---

## 9. Website behaviour

- Forms show an **optional** checkbox: *Send me rate updates and home loan tips by email*.
- If ticked → lead syncs to Mailchimp after save (non-blocking; lead always saves).
- Admin → **Enquiries** shows opt-in and sync status.
- Backfill old opted-in leads: `php artisan mailchimp:sync-enquiries` (use `--dry-run` first).

---

## 10. SMS (optional, later)

Mailchimp → **SMS** on a paid plan. Use for short hot-lead follow-ups only, with consent. No website code required for v1.

---

## 11. Retargeting (complements Mailchimp)

- **Mailchimp** → people you have email for (opted in).
- **Google/Meta ads** → visitors who didn’t enquire (GA4/GTM audiences).

See [lead-generation-playbook.md](lead-generation-playbook.md) and [SEO-FUNNELS-ADS.md](SEO-FUNNELS-ADS.md).

---

## Checklist

- [ ] Domain verified in GoDaddy DNS
- [ ] Audience created; Audience ID copied
- [ ] API key in production `.env`; `MAILCHIMP_ENABLED=true`
- [ ] Test form submit with marketing checkbox → contact in Mailchimp with tags
- [ ] Welcome automation live on tag `website-lead`
- [ ] Kal’s customer CSV imported with tag `client`
- [ ] First newsletter sent to test contacts
