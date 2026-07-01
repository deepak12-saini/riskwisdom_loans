# SEO, Funnels & Paid Ads — Riskwisdom Loans

**For:** Kal  
**Site:** https://riskwisdomloans.com.au  
**Status:** Implemented — enable GTM + Google Ads when ready

---

## Summary

The website now has:

- **SEO landing pages** targeting refinance and home-loan keywords
- **Conversion funnels** → rate review, book a call, calculators, contact
- **Tracking** → GTM events for leads (`generate_lead`, `form_submit`)
- **UTM attribution** → paid ad leads visible in admin

---

## Keyword → page map

| Target keyword | URL | Primary CTA |
|----------------|-----|-------------|
| refinance home loan | `/refinance` | Rate review |
| refinance home loan rates | `/refinance-home-loan-rates` | Rate review |
| refinance home loan calculator | `/refinance-home-loan-calculator` | Repayment calculator |
| refinance cashback / offers | `/refinance-cashback-offers` | Rate review |
| home loans australia | `/home-loans` | Contact / loan review |
| first home buyer loan | `/first-home-buyer` | Book a call |
| borrowing power calculator | `/tools/borrowing-power` | Gated calculator lead |
| stamp duty calculator | `/tools/stamp-duty` | Calculator + contact |
| am I on the right rate | `/rate-review` | Rate review form |

### Paid ads — conversion landing pages (recommended)

Minimal-distraction pages with headline + pre-qual enquiry form. Use these for **Google Ads** and **Meta ads** instead of the homepage.

| Campaign | URL | Pre-filled loan type |
|----------|-----|----------------------|
| Generic | `/enquire` | User selects |
| Refinance | `/enquire/refinance` | Refinance |
| Home loans | `/enquire/home-loans` | Home purchase |
| First home buyer | `/enquire/first-home-buyer` | Home purchase |
| Investment | `/enquire/investment` | Investment property |
| Commercial | `/enquire/commercial` | Commercial |

**Example Google Ads URL:**

```
https://riskwisdomloans.com.au/enquire/refinance?utm_source=google&utm_medium=cpc&utm_campaign=refinance
```

Or in PHP: `conversion_landing_url('refinance')`

Form captures: name, phone, email, **what they need**, timeline, state, and free-text requirement. Leads appear in admin as **Ad / conversion landing** with UTM tags.

---

## Funnel flow

```
Google organic / paid ad / social
        ↓
Keyword-matched landing page
        ↓
CTA: Rate review · Book a call · Calculator · Contact
        ↓
Lead saved → Admin → Enquiries
        ↓
(Optional) Convert to client file → tasks → DocuSign
```

---

## Google Search campaigns (recommended start)

**Test budget:** $30–50/day (~$1,000/month) for 4–6 weeks.

**Full business plan for approval:** [`docs/GOOGLE-ADS-BUSINESS-PLAN.md`](GOOGLE-ADS-BUSINESS-PLAN.md)

### Campaign 1 — Refinance

- **Keywords:** refinance home loan, refinance home loan rates, refinance home loan australia
- **Landing page:** `/refinance-home-loan-rates`
- **Sample URL:**
  ```
  https://riskwisdomloans.com.au/refinance-home-loan-rates?utm_source=google&utm_medium=cpc&utm_campaign=refinance_rates
  ```

### Campaign 2 — Calculators

- **Keywords:** borrowing power calculator, home loan calculator australia
- **Landing page:** `/tools/borrowing-power`
- **Sample URL:**
  ```
  https://riskwisdomloans.com.au/tools/borrowing-power?utm_source=google&utm_medium=cpc&utm_campaign=borrowing_calc
  ```

### Campaign 3 — First home buyer

- **Keywords:** first home buyer loan, first home buyer mortgage australia
- **Landing page:** `/first-home-buyer`
- **Sample URL:**
  ```
  https://riskwisdomloans.com.au/first-home-buyer?utm_source=google&utm_medium=cpc&utm_campaign=fhb
  ```

**Conversion tracking:** Import GA4 `generate_lead` event into Google Ads after GTM is live. Setup: [`docs/GSC-GTM-SETUP.md`](GSC-GTM-SETUP.md).

---

## What Kal needs to do

| Task | Why | Billing? |
|------|-----|----------|
| Set `GOOGLE_TAG_MANAGER_ID` in production `.env` | Measure leads | **Free** — see [lead-generation-playbook.md](lead-generation-playbook.md) |
| Create GA4 property + link in GTM | Conversions | **Free** |
| Google Search Console — verify site, submit sitemap | Organic SEO | **Free** |
| Google Business Profile — claim listing | Local leads | **Free** |
| Google Ads account + billing | Paid campaigns | **Paid** — only when budget approved |
| Approve test ad budget (~$1k/month) | Safe test spend |

---

## Admin reporting

- **All leads:** `/admin/enquiries`
- **Paid ad leads:** filter **Paid ads** (utm_medium = cpc)
- **Export CSV** for monthly review

---

## Monthly KPIs (first 90 days)

| Metric | Target |
|--------|--------|
| GSC impressions | Rising month-on-month |
| Website leads (admin) | 20+ / month organic; scale with ads |
| Cost per lead (paid) | Under $80–150 AUD (varies by market) |
| Ready-now leads | Call within 1 hour (business hours) |

---

## Google Ads launch checklist (ops)

- [ ] GTM live on production with `generate_lead` conversion
- [ ] GA4 receiving events (test form submit → thank-you page)
- [ ] Google Ads account created under Riskwisdom business
- [ ] Conversion imported from GA4
- [ ] 3 campaigns created (refinance, calculator, FHB)
- [ ] Landing URLs include UTMs (see samples above)
- [ ] Daily budget set ($30–50/day total across campaigns)
- [ ] Negative keywords added after 2 weeks (jobs, salary, free download, etc.)
- [ ] Weekly review: admin enquiries + Ads cost per conversion

---

## One-paragraph summary for WhatsApp

SEO pages and funnels are live on the website — refinance keywords, calculators, rate review, and book a call. Tracking is ready once GTM is added to `.env`. Recommend starting Google Search ads at ~$1k/month on refinance + calculator pages with UTM links so we can see which ads bring leads in admin.
