# Google Ads — Step-by-Step Beginner Guide

**For:** Riskwisdom Loans (first time using Google Ads)  
**Website:** https://riskwisdomloans.com.au  
**Goal:** Get phone calls and form leads (rate review, book a call)  
**Time to set up first campaign:** about 1–2 hours  

---

## Part 0 — Understand how it works (5 minutes)

### What Google Ads is

You pay Google to show your website when someone searches keywords like:

- `refinance home loan australia`
- `mortgage broker near me`
- `free rate review home loan`

**You are NOT paying for the ad to exist.** You pay when someone **clicks** your ad (Pay-Per-Click = PPC).

### Simple flow

```
Person searches on Google
        ↓
Your ad appears (if you bid on that keyword)
        ↓
They click → go to your landing page (e.g. /rate-review)
        ↓
They fill form or call you
        ↓
Lead appears in /admin/enquiries (with "Paid ads" tag if UTM is set)
        ↓
You call them fast → loan application → settlement → your income
```

### What you need before starting

| Item | Status for Riskwisdom |
|------|------------------------|
| Live website | ✅ riskwisdomloans.com.au |
| Rate review page | ✅ /rate-review |
| Google account (Gmail) | You create |
| Credit/debit card for billing | Required by Google |
| Google Analytics (GA4) | ✅ G-N0BBL44LY1 (see GSC-GTM-SETUP.md) |
| Search Console verified | ✅ You did this |

**Recommended test budget:** $30–50 AUD **per day** (~$900–$1,500/month). Start lower ($20/day) if you want to learn first.

---

## Part 1 — Create your Google Ads account (15 minutes)

### Step 1.1 — Go to Google Ads

1. Open: https://ads.google.com  
2. Click **Start now** or **Sign in**  
3. Use a **Gmail account** you will keep (business email is fine if linked to Google)

### Step 1.2 — Switch to Expert Mode (important)

Google tries to push "Smart campaigns" for beginners. For control, use **Expert Mode**:

1. After sign-up, if you see a simplified wizard, look for **Switch to Expert Mode** (often bottom-left or under "Experienced with Google Ads?")  
2. Click it — you get full control over campaigns

### Step 1.3 — Set account basics

1. **Business name:** Riskwisdom Loans  
2. **Website:** https://riskwisdomloans.com.au  
3. **Country / timezone:** Australia (AEST)  
4. **Currency:** AUD  

### Step 1.4 — Add billing

1. Go to **Tools & settings** (wrench icon) → **Billing** → **Settings**  
2. Add payment method (credit or debit card)  
3. Google may charge a small verification amount (refunded)

> **Note:** Ads do not run until billing is active and campaign is approved.

---

## Part 2 — Link Google Analytics (10 minutes)

Linking helps track which clicks become leads.

1. In Google Ads: **Tools & settings** → **Linked accounts** → **Google Analytics (GA4)**  
2. Link property **G-N0BBL44LY1** (Riskwisdom Loans)  
3. Enable **Auto-tagging** in Google Ads:  
   - **Settings** → **Account settings** → **Auto-tagging** → **ON**  

This adds `gclid` to URLs so Google knows which ad click led to a visit.

---

## Part 3 — Set up conversion tracking (20 minutes)

A **conversion** = someone submits a form or completes a valuable action.

Your site fires `generate_lead` on the thank-you page (via GTM). Google Ads needs to know about this.

### Option A — Import from GA4 (recommended)

**Prerequisite:** GTM live on production (`GTM-N65TM293`). See `docs/GSC-GTM-SETUP.md`.

1. In **Google Ads** → **Goals** → **Conversions** → **Summary**  
2. Click **+ New conversion action**  
3. Choose **Import** → **Google Analytics 4 properties**  
4. Select **generate_lead** (or form submit events)  
5. Set as **Primary** conversion for bidding  

### Option B — Test without conversion first (first 3 days)

You can run ads and track leads manually in `/admin/enquiries` using UTM links (Part 5). Add conversion import within the first week.

### Test that tracking works

1. Open site in browser (not logged in as admin)  
2. Submit **rate review** form with test data  
3. Check **GA4 Realtime** → Events → `generate_lead`  
4. If it appears, tracking works ✅  

---

## Part 4 — Create your FIRST campaign (30 minutes)

Start with **one** campaign: **Rate Review — Refinance**.

Why? `/rate-review` is short, clear, and built to capture leads fast.

### Step 4.1 — New campaign

1. Click **+ New campaign**  
2. **Objective:** Leads (or **Sales** → website leads)  
3. **Campaign type:** **Search** (text ads on Google search — best for brokers)  
4. **Conversion goal:** Select `generate_lead` if imported; otherwise continue  

### Step 4.2 — Campaign settings

| Setting | What to choose |
|---------|----------------|
| Campaign name | `Rate Review - Refinance` |
| Networks | **Search only** — uncheck Display Network and Search Partners (for now) |
| Locations | Australia (or your state/cities if you only serve certain areas) |
| Languages | English |
| Budget | **$20–30 per day** to start |
| Bidding | Start with **Maximize clicks** (learning phase). After 2–4 weeks with conversions, switch to **Maximize conversions** |

### Step 4.3 — Keywords (what triggers your ad)

Add these as **Phrase match** or **Exact match** (more control, less wasted spend):

```
refinance home loan
refinance home loan australia
refinance mortgage broker
home loan rate review
compare home loan rates
refinance my home loan
```

**How to add:**

1. In campaign → **Keywords** → **+ Keywords**  
2. Paste list above  
3. Match type: start with **Phrase match** (shows as `"refinance home loan"`)  

### Step 4.4 — Negative keywords (save money)

Add these so you don't pay for wrong searches:

```
jobs
salary
career
free download
calculator app
commbank careers
nab careers
how to become a broker
```

Go to **Keywords** → **Negative keywords** → add to campaign.

### Step 4.5 — Landing page URL (with tracking)

Use this **exact** URL in your ads so admin shows "Paid ads":

```
https://riskwisdomloans.com.au/rate-review?utm_source=google&utm_medium=cpc&utm_campaign=rate_review
```

**Final URL** in each ad = that link above.

Your website saves `utm_source`, `utm_medium`, `utm_campaign` on each lead. In admin → **Enquiries** → filter **Paid ads**.

### Step 4.6 — Write your ads (Responsive Search Ads)

Google asks for headlines and descriptions. Example:

**Headlines (use 8–12):**

```
Free Home Loan Rate Review
Am I On The Right Rate?
Refinance Home Loan Help
Compare Your Mortgage Rate
Australian Mortgage Broker
Fast Callback - No Obligation
Riskwisdom Loans
Check If You Can Save
Refinance Rates Australia
```

**Descriptions:**

```
Not sure if your home loan rate is still competitive? Request a free rate review. We call you back quickly.
Compare refinance options with clear broker guidance. No pressure — practical advice for Australian homeowners.
```

**Display path (optional):** `riskwisdomloans.com.au / Rate-Review`

### Step 4.7 — Review and publish

1. Check **Estimated performance** (clicks, cost — estimates only)  
2. Click **Publish campaign**  
3. Ads usually go to review → live within **24 hours** (sometimes a few hours)

---

## Part 5 — How to know ads are working

### In Google Ads (daily)

| Report | Where | What to look at |
|--------|-------|-----------------|
| Campaigns | Overview | Clicks, Cost, CTR |
| Conversions | Campaigns column | Leads (after conversion linked) |
| Search terms | Keywords → Search terms | What people actually typed — add bad terms as negatives |

### On your website (most important for leads)

1. Log in: https://riskwisdomloans.com.au/admin/enquiries  
2. Click filter **Paid ads**  
3. Leads with `utm_medium = cpc` came from Google Ads  
4. **Call within 15 minutes** during business hours  

### Weekly checklist (15 minutes)

- [ ] How much spent? (Google Ads → Campaigns)  
- [ ] How many clicks?  
- [ ] How many leads in admin (Paid ads filter)?  
- [ ] Cost per lead = Spend ÷ Leads (e.g. $300 spend ÷ 3 leads = $100 per lead)  
- [ ] Add 5–10 negative keywords from Search terms report  

---

## Part 6 — Second campaign (after 2 weeks)

When first campaign is running, add:

### Campaign 2 — First home buyer

| Item | Value |
|------|--------|
| Landing page | `https://riskwisdomloans.com.au/first-home-buyer?utm_source=google&utm_medium=cpc&utm_campaign=fhb` |
| Keywords | first home buyer loan, first home buyer mortgage australia |
| Budget | $15–20/day |

### Campaign 3 — Refinance landing page

| Item | Value |
|------|--------|
| Landing page | `https://riskwisdomloans.com.au/refinance-home-loan-rates?utm_source=google&utm_medium=cpc&utm_campaign=refinance_rates` |
| Keywords | refinance home loan rates, refinance rates australia |

Full keyword map: see `docs/SEO-FUNNELS-ADS.md`.

---

## Part 7 — Rules that protect your money

1. **Start small** — $20–30/day total until you see leads  
2. **Search only** — no Display Network at first  
3. **Use negative keywords** every week  
4. **One landing page per theme** — rate review for refinance intent, not homepage  
5. **Call leads fast** — slow follow-up wastes ad spend  
6. **Pause** any keyword that spends $50+ with zero leads  
7. **Never** send ads to `/thank-you` or admin pages  

---

## Part 8 — Glossary (simple)

| Term | Meaning |
|------|---------|
| **Campaign** | Top level — e.g. "Rate Review Refinance" |
| **Ad group** | Group of keywords + ads inside a campaign |
| **Keyword** | Word people search that triggers your ad |
| **CPC** | Cost per click — what you pay when someone clicks |
| **CTR** | Click-through rate — % of people who click after seeing ad |
| **Conversion** | Lead / form submit / call (what you want) |
| **Impression** | Ad shown on screen (you often don't pay unless they click) |
| **Quality Score** | Google's rating of ad + page relevance (higher = cheaper clicks) |
| **UTM** | Tags on URL (`utm_source=google`) so you know lead came from ads |
| **Negative keyword** | Search term where you do NOT want your ad to show |

---

## Part 9 — Troubleshooting

| Problem | Fix |
|---------|-----|
| No impressions | Budget too low, keywords too narrow, or ads still in review — wait 24h |
| Clicks but no leads | Check landing page on mobile; call button visible? Form working? |
| Leads not in "Paid ads" | URL missing `?utm_medium=cpc` — fix Final URL in ads |
| Too expensive | Add negative keywords; narrow to phrase/exact match; improve ad text |
| Ad disapproved | Finance ads may need extra info — check Policy manager in Google Ads |
| Conversion not tracking | Complete GTM setup (`docs/GSC-GTM-SETUP.md`); test thank-you page event |

---

## Part 10 — Your first-day action list

Do these in order today:

1. [ ] Create Google Ads account → **Expert Mode**  
2. [ ] Add billing  
3. [ ] Link GA4 property  
4. [ ] Create **one** Search campaign: Rate Review  
5. [ ] Final URL: `https://riskwisdomloans.com.au/rate-review?utm_source=google&utm_medium=cpc&utm_campaign=rate_review`  
6. [ ] Add 6–10 keywords + 10 negative keywords  
7. [ ] Publish campaign  
8. [ ] Submit `/rate-review` for indexing in Search Console (free — helps quality score)  
9. [ ] Tomorrow: check admin enquiries for paid leads  
10. [ ] Call every lead within 15 minutes  

---

## Part 11 — What success looks like (first 30 days)

| Week | Expect |
|------|--------|
| Week 1 | Ads live, 10–50 clicks, learning phase, 0–2 leads normal |
| Week 2 | Add negatives, tweak ads, 2–5 leads possible |
| Week 3–4 | Cost per lead stabilises; scale budget if leads are profitable |

**Example maths:**  
If you spend $600/month and get 6 leads → $100 per lead.  
If 2 become settlements and broker trail/payment covers cost → ads are worth continuing.

---

## Related docs in this project

| Doc | Purpose |
|-----|---------|
| `docs/GOOGLE-ADS-BUSINESS-PLAN.md` | Strategy, keywords, landing pages, budget — for Kal / approval |
| `docs/GSC-GTM-SETUP.md` | Google Analytics + conversion tracking |
| `docs/SEO-FUNNELS-ADS.md` | Keyword → page map + campaign URLs |
| `docs/MAILCHIMP-SETUP.md` | Email follow-up for leads |

---

## One-page summary (print this)

```
GOOGLE ADS — RISKWISDOM LOANS QUICK START

1. ads.google.com → Expert Mode → Billing ON
2. One Search campaign: "Rate Review - Refinance"
3. Budget: $25/day | Location: Australia | English
4. Landing URL:
   https://riskwisdomloans.com.au/rate-review?utm_source=google&utm_medium=cpc&utm_campaign=rate_review
5. Keywords: refinance home loan, home loan rate review, refinance mortgage broker
6. Negatives: jobs, salary, career, free download
7. Check leads: /admin/enquiries → Paid ads
8. Call leads within 15 minutes
9. Review weekly: spend, clicks, cost per lead
10. Scale slowly if leads convert to settlements
```

---

*Last updated: July 2026 — Riskwisdom Loans marketing setup*
