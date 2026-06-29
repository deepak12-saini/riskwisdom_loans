# Off-site Lead Generation Playbook

Operational checklist for Kal and the Riskwisdom Loans team. The website now captures qualified leads — these channels drive traffic to it.

## Google Business Profile (start this week)

1. Claim or verify **Riskwisdom Loans** at [business.google.com](https://business.google.com)
2. Use consistent NAP: Riskwisdom Loans · +61 421 670 636 · info@riskwisdomloans.com.au · riskwisdomloans.com.au
3. Add services: Home loans, Refinance, Investment property loans, Commercial finance
4. Upload office/broker photos and logo
5. Post weekly (rate tip, first home buyer tip, refinance reminder)
6. Ask satisfied clients for Google reviews (comply with review guidelines)

## Google Search Console

1. Add property: `https://riskwisdomloans.com.au`
2. Verify via DNS or HTML tag
3. Submit sitemap: `https://riskwisdomloans.com.au/sitemap.xml`
4. Monitor queries monthly for refinance / home loan terms

## Analytics (configure in `.env`)

**Cost:** Google Tag Manager, GA4, Search Console, and Microsoft Clarity are **free**. No credit card required for Phase A.  
**Billing only needed later** if you run Google Ads (paid campaigns).

### Where to get `GOOGLE_TAG_MANAGER_ID` (free — ~10 minutes)

1. Open **[tagmanager.google.com](https://tagmanager.google.com)** and sign in with a Google account (use `info@riskwisdomloans.com.au` or your work Gmail).
2. Click **Create account**.
   - **Account name:** `Riskwisdom Loans`
   - **Country:** Australia
3. **Container setup:**
   - **Container name:** `riskwisdomloans.com.au`
   - **Target platform:** **Web**
4. Click **Create** → accept the terms.
5. You will see **Install Google Tag Manager** — the ID is in the box at the top, format:
   ```
   GTM-XXXXXXX
   ```
   Example: `GTM-ABC1234` (yours will be different).
6. Copy that ID into `.env` (local and production):

```env
GOOGLE_TAG_MANAGER_ID=GTM-XXXXXXX
```

7. On the **server** (Plesk), edit `/var/www/vhosts/riskwisdomloans.com.au/httpdocs/.env` (or your deploy path), add the same line, then run:
   ```bash
   php artisan config:clear
   php artisan config:cache
   ```

8. **Verify:** Visit the live site → right-click → **View page source** → search for `googletagmanager.com` — you should see your `GTM-XXXXXXX` in the script.

> You do **not** need to paste the GTM HTML snippet into Blade files — the site already loads GTM from `resources/views/partials/tracking-head.blade.php` when `GOOGLE_TAG_MANAGER_ID` is set.

### GA4 Measurement ID (free — needed inside GTM)

GTM alone does not store analytics; you connect **Google Analytics 4** inside GTM:

1. Open **[analytics.google.com](https://analytics.google.com)** → **Admin** (gear icon).
2. **Create** → **Property** → name: `Riskwisdom Loans` → timezone Australia → industry Finance.
3. **Data stream** → **Web** → URL: `https://riskwisdomloans.com.au` → stream name: `Website`.
4. Copy the **Measurement ID** (format `G-XXXXXXXXXX`) — you will use this **inside GTM**, not in Laravel `.env`.

### Microsoft Clarity (optional, free)

1. Open **[clarity.microsoft.com](https://clarity.microsoft.com)** → sign in → **Add new project**.
2. Site URL: `https://riskwisdomloans.com.au`
3. Copy the **Project ID** into `.env`:

```env
MICROSOFT_CLARITY_ID=your-project-id
```

### Full `.env` example (analytics only)

```
GOOGLE_TAG_MANAGER_ID=GTM-XXXXXXX
MICROSOFT_CLARITY_ID=
```

Leave `MICROSOFT_CLARITY_ID` empty if you skip Clarity for now.

### GTM conversion events (website pushes these to `dataLayer`)

| Event | When | Use in GA4 |
|-------|------|------------|
| `generate_lead` | Thank-you page after form submit | **Mark as conversion** |
| `form_submit` | Contact, rate review, borrowing power submit | Funnel step |
| `form_start` | First focus on contact form | Abandonment analysis |
| `cta_click` | Any `[data-cta]` button click | Landing page engagement |
| `click_phone` | Phone link taps | Call intent |
| `book_chat_click` | Calendly / book links | Booking intent |

### GTM setup steps (after you have GTM-XXXXXXX and G-XXXXXXXXXX)

1. In [tagmanager.google.com](https://tagmanager.google.com) → your container → **Tags** → **New**.
2. **Tag type:** Google Analytics → **Google Tag** (or GA4 Configuration).
3. **Tag ID:** paste your GA4 Measurement ID (`G-XXXXXXXXXX`).
4. **Trigger:** All Pages → Save.
5. **Tags** → **New** again for events:
   - **Tag type:** Google Analytics → **GA4 Event**
   - **Configuration tag:** the tag from step 2
   - **Event name:** `generate_lead`
   - **Trigger:** Custom Event → Event name: `generate_lead`
6. Repeat for `form_submit` and `book_chat_click` (optional).
7. Click **Submit** (top right) → **Publish** container.
8. In GA4 → **Admin** → **Events** → wait 24h or use **DebugView** → mark `generate_lead` as a **conversion**.
9. **Google Ads** (only when you add billing later) → Goals → import GA4 `generate_lead`.

**Quick test:** Submit a test lead on the site → open GA4 → **Realtime** — you should see 1 active user and events within a few minutes.

### Ad landing URLs

Use the `ad_landing_url()` helper in PHP or append UTMs manually:

```
https://riskwisdomloans.com.au/refinance-home-loan-rates?utm_source=google&utm_medium=cpc&utm_campaign=refinance_rates
```

See [`docs/SEO-FUNNELS-ADS.md`](SEO-FUNNELS-ADS.md) for full keyword and campaign map.

---

## Phase A checklist (Deepak — no billing required)

| Step | Done? |
|------|-------|
| Create GTM container → copy `GTM-XXXXXXX` | |
| Create GA4 property → copy `G-XXXXXXXXXX` | |
| Add `GOOGLE_TAG_MANAGER_ID` to production `.env` | |
| Publish GTM tags (GA4 config + `generate_lead` event) | |
| Mark `generate_lead` as conversion in GA4 | |
| GSC: verify domain + submit sitemap | |
| Test: submit form → check GA4 Realtime | |

Google Ads and billing can wait until Kal approves ad budget.

## Referral partners

- Share partner page: `https://riskwisdomloans.com.au/partners`
- Target: accountants, buyers agents, real estate agents, planners
- Offer clear referral handoff: client name, loan type, timeline, best contact time

## Organic social (2 posts/week minimum)

**LinkedIn (Kal personal + company if available)**

- Refinance triggers in 2026
- First home buyer document tips
- “What a broker actually does” education posts
- Link to guides: `/guides`

**Facebook community groups**

- Answer questions helpfully in local property / FHB groups
- Do not spam links — build trust, then invite DMs or website enquiry

## Paid ads (when budget confirmed)

### Google Search (highest intent)

- Start: $30–50/day
- Keywords: refinance home loan, mortgage broker [city], home loan broker australia
- Landing pages: `/refinance`, `/home-loans`, `/first-home-buyer`
- Conversion: thank-you page URL

### Meta (Facebook/Instagram)

- Audience: homeowners 30–55, property interest, Australia
- Creative: calculator tools, refinance checklist guide
- Landing: `/tools/borrowing-power` or `/guides`

### Retargeting

- Build GA4/GTM audience of site visitors who did not reach `/thank-you`
- Remarket with “Free loan review” creative

## Lead response SLA

| Timeline submitted | Target response |
|--------------------|-----------------|
| Ready now | Within 1 hour (business hours) |
| 1–3 months | Within 24 hours |
| Researching | Within 48 hours + nurture email |

**Speed wins.** Most broker leads go to whoever calls back first.

## Monthly review

1. Admin enquiries: `/admin/enquiries` — export CSV
2. GA4: form conversion rate, top landing pages, phone clicks
3. Clarity: form abandonment recordings
4. Which loan types and states convert best?
5. Double down on best channel; pause what does not convert
