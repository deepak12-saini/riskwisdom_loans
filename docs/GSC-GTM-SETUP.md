# GSC + GTM setup — do this now (~15 minutes)

**Site:** https://riskwisdomloans.com.au  
**GTM container:** `GTM-N65TM293`  
**GA4 Measurement ID:** `G-N0BBL44LY1`

GSC and GTM are configured in Google’s web UI — this checklist uses your real IDs.

---

## Part 1 — Fix sitemap on server (before GSC)

After deploying the latest code (SitemapController fix), on **cPanel Terminal**:

```bash
cd /path/to/laravel/root
php artisan route:clear
php artisan config:clear
php artisan config:cache
```

**Verify in browser:** https://riskwisdomloans.com.au/sitemap.xml  
You should see XML with URLs including `/refinance-home-loan-rates`.

---

## Part 2 — Google Search Console

Open [search.google.com/search-console](https://search.google.com/search-console) → property `riskwisdomloans.com.au`.

### A. Submit sitemap

1. Left menu → **Indexing** → **Sitemaps**
2. Under “Add a new sitemap”, enter: `sitemap.xml`
3. Click **Submit**
4. Status should become **Success** (may take a few minutes)

### B. Request indexing (priority pages)

1. Top bar → **URL inspection**
2. Paste each URL → **Test live URL** → **Request indexing**:

| URL |
|-----|
| `https://riskwisdomloans.com.au/refinance-home-loan-rates` |
| `https://riskwisdomloans.com.au/refinance` |
| `https://riskwisdomloans.com.au/home-loans` |

Google limits requests per day — these three are the priority set.

### C. Confirm later (1–2 weeks)

- **Performance** → rising impressions
- Search `site:riskwisdomloans.com.au` → more than one page indexed

---

## Part 3 — Google Tag Manager (publish tags)

Open [tagmanager.google.com](https://tagmanager.google.com) → container **GTM-N65TM293**.

### Tag 1 — GA4 Google Tag (all pages)

1. **Tags** → **New**
2. Name: `GA4 - Google Tag`
3. Tag type: **Google Tag**
4. Tag ID: `G-N0BBL44LY1`
5. **Triggering** → **All Pages**
6. **Save**

### Trigger — Custom event `generate_lead`

1. **Triggers** → **New**
2. Name: `CE - generate_lead`
3. Type: **Custom Event**
4. Event name: `generate_lead`
5. **Save**

### Tag 2 — GA4 Event `generate_lead`

1. **Tags** → **New**
2. Name: `GA4 - Event generate_lead`
3. Tag type: **Google Analytics: GA4 Event**
4. Measurement ID: `G-N0BBL44LY1` (or select Tag 1 as configuration tag)
5. Event name: `generate_lead`
6. **Triggering** → `CE - generate_lead`
7. **Save**

### (Optional) Tag 3 — GA4 Event `form_submit`

1. Create trigger: Custom Event → `form_submit`
2. Create tag: GA4 Event → event name `form_submit`

### Publish

1. Top right → **Submit**
2. Version name: `GA4 + generate_lead conversion`
3. **Publish**

---

## Part 4 — GA4 mark conversion

1. Open [analytics.google.com](https://analytics.google.com) → property **Riskwisdom Loans**
2. **Admin** → **Data display** → **Events**
3. Submit a **test enquiry** on the live site (use a test email)
4. On thank-you page, site fires `generate_lead` (see `thank-you.blade.php`)
5. In GA4 **Realtime** or **Events**, find `generate_lead`
6. Toggle **Mark as conversion** next to `generate_lead`

---

## Part 5 — Quick test

| Step | Expected |
|------|----------|
| Homepage view-source | Contains `GTM-N65TM293` |
| GA4 Realtime while browsing site | 1 active user |
| Submit test contact form | Thank-you page loads |
| GA4 Realtime / Events | `generate_lead` appears |
| Admin `/admin/enquiries` | Test lead saved |

---

## Checklist

- [ ] `sitemap.xml` loads on production (not 500)
- [ ] GSC sitemap submitted
- [ ] GSC indexing requested for 3 priority URLs
- [ ] GTM: GA4 Google Tag published
- [ ] GTM: `generate_lead` event tag published
- [ ] GA4: `generate_lead` marked as conversion
- [ ] Test lead → Realtime shows event

---

## Part 6 — Meta Pixel (Facebook / Instagram ads)

When running Meta ads, add the pixel ID to production `.env`:

```env
META_PIXEL_ID=your_pixel_id_here
```

The site will automatically:

- Fire **PageView** on every page
- Fire **Lead** on thank-you page (after form submit)
- Fire **Schedule** when someone books on `/book` (Calendly)

**Verify:** Meta Events Manager → Test events → browse site and submit a test enquiry.

**Optional in GTM:** You can also add the pixel via GTM instead of `.env` — use one method only to avoid duplicate events.

---

## Part 7 — Ad landing pages + tracking map

| Traffic source | Landing URL | Lead in admin | GTM event |
|----------------|-------------|---------------|-----------|
| Google Ads refinance | `/enquire/refinance?utm_...` | `conversion` + Paid ads filter | `form_submit` → `generate_lead` |
| Google Ads FHB | `/enquire/first-home-buyer?utm_...` | same | same |
| Organic SEO | `/refinance`, `/home-loans` | contact / rate_review | `generate_lead` |
| Book a call | `/book` | Calendly (not in enquiries) | `book_appointment` |

**PHP helper for ad URLs:** `conversion_landing_url('refinance')`

---

When Kal approves budget: **Google Ads** → **Goals** → **Import** → select GA4 `generate_lead` conversion.
