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

```
GOOGLE_TAG_MANAGER_ID=GTM-XXXXXXX
MICROSOFT_CLARITY_ID=your-clarity-id
```

Create GA4 in GTM and mark **generate_lead** (thank-you page) as a conversion.

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
