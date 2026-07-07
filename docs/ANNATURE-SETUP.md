# Annature setup — Riskwisdom Loans

**For:** Kal  
**Site:** https://riskwisdomloans.com.au  
**Annature dashboard:** https://dashboard.annature.com.au  

Annature is the **default e-sign provider** for admin document sending (Australian alternative to DocuSign).

---

## 1. Finish Kal’s profile (onboarding)

On **Signatures** step:

| Item | What to add |
|------|-------------|
| **Signature** | Kaltaran Bhinder’s full signature |
| **Initials** | e.g. **KB** |
| **Stamp** | Optional — only if Kal has a business stamp image |

Clients create **their own** signature when they open the signing email — you do not add client signatures here.

---

## 2. Create API keys (Developers)

1. Annature → **Developers**
2. **Create API key** (name e.g. `Riskwisdom Loans website`)
3. Copy and save securely (shown once):
   - **Public key** (`X-Annature-Id`)
   - **Private key** (`X-Annature-Key`)
4. Copy your **Account ID** from the dashboard (used when creating envelopes)

Store in password manager — not in email or chat.

### Production `.env`

```env
SIGNING_PROVIDER=annature
ANNATURE_ENABLED=true
ANNATURE_PUBLIC_KEY=your-public-key
ANNATURE_PRIVATE_KEY=your-private-key
ANNATURE_ACCOUNT_ID=your-account-id
ANNATURE_WEBHOOK_SECRET=your-endpoint-signing-secret
```

To use DocuSign instead, set `SIGNING_PROVIDER=docusign` and configure `DOCUSIGN_*` keys.

---

## 3. Webhook endpoint (Developers → Endpoints)

Annature notifies the website when a client **completes** or **declines** a document. Signed PDFs are stored automatically on the client file.

### Production URL

```
https://riskwisdomloans.com.au/webhooks/annature
```

### Steps in dashboard

1. Deploy the site with Annature integration (route `/webhooks/annature` is live)
2. **Developers** → **Endpoints** → **Create endpoint**
3. **URL:** paste the HTTPS URL above
4. Save — Annature shows a **signing secret** for this endpoint
5. Copy signing secret → `ANNATURE_WEBHOOK_SECRET` in production `.env`

### Important

- URL must be **HTTPS** (not `http://127.0.0.1`)
- Endpoint must return **2xx** within **10 seconds**
- Annature sends from IP **`52.62.153.44`** (allow on firewall if needed)
- Verify requests using header **`X-Annature-Signature`**

For **local testing**, use a tunnel (e.g. ngrok) and a separate staging endpoint URL.

---

## 4. Send from admin (integrated)

1. Open **client file** in admin (`/admin/clients/{id}`)
2. Scroll to **E-sign documents**
3. Choose document type, title, signer details, upload PDF
4. Click **Send via Annature**
5. Client receives signing email from Annature
6. When signed, webhook stores the PDF on the client file (or use **Sync status** manually)

Link an open **task** when sending — it auto-closes when the document is signed.

### Signature placement (tap-to-sign, not “choose location”)

Admin sends envelopes with a **pre-placed signature field** so clients tap the box and sign — they do not drag or pick a location.

| Mode | When to use |
|------|-------------|
| **coordinates** (default) | Any PDF — field is placed on the **last page**, bottom-left, sized to fit the PDF |
| **anchor** | PDF includes hidden text `{{signature}}` on the signature line |

**Recommended rollout:** keep `coordinates` as the default for reliable admin uploads. Use `anchor` only for standard broker forms you control and have tested.

**For broker templates with anchor text:** add `{{signature}}` in small/light text on each PDF’s signature line, then in `.env`:

```env
ANNATURE_SIGNATURE_PLACEMENT=anchor
```

Or enable per document type in `config/annature.php` → `document_type_placement`.

**If the client sees PDF only with no signature box:** the old fixed coordinates could land outside the page (Annature skips those fields silently). Deploy the latest code, then void the envelope and send again. For standard broker forms, add `{{signature}}` anchor text for exact placement.

---

## 5. Typical documents to send

| Document | Signer |
|----------|--------|
| Privacy consent | Client |
| Credit guide acknowledgment | Client |
| Authority to act / broker appointment | Client (sometimes client + broker) |

---

## 6. Annature vs DocuSign on this website

| Feature | Annature (default) | DocuSign |
|---------|-------------------|----------|
| Send from admin | Yes (`SIGNING_PROVIDER=annature`) | Yes (`SIGNING_PROVIDER=docusign`) |
| Webhook stores signed PDF | `/webhooks/annature` | `/webhooks/docusign` |
| AU-focused support | Australian | Global |

---

## 7. Checklist for Kal

- [ ] Onboarding complete (signature + initials)
- [ ] API key created and stored securely
- [ ] Account ID copied
- [ ] Test send one envelope to yourself from admin
- [ ] Webhook endpoint created on production after deploy
- [ ] API keys + webhook secret added to production `.env`
- [ ] Test signed document appears in admin with **Download PDF**

---

## Links

- [Annature API docs](https://docs.annature.com.au/api-reference/introduction)
- [Webhooks](https://docs.annature.com.au/api-reference/webhooks)
- [Create endpoint](https://docs.annature.com.au/api-reference/endpoints/create-endpoint)
- DocuSign setup (alternative): [`docs/DOCUSIGN-SETUP.md`](DOCUSIGN-SETUP.md)
