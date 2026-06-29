# Riskwisdom Loans — Phase 2 Scope of Work

**Prepared for:** Kal  
**Prepared by:** Deepak  
**Date:** June 2026  
**Status:** Phase 2A complete · Phase 2B (DocuSign) built — enable with `DOCUSIGN_*` keys in `.env`  

---

## Purpose

Phase 1 delivers website leads (booking, calculators, rate review, contact forms). Phase 2 covers **back-office operations**: tracking outstanding client tasks and sending/storing digitally signed documents.

Please review, confirm, or note changes in **Section 7** before development starts.

---

## Phase 1 recap (complete / deployed)

| Item | Status |
|------|--------|
| Calendly booking (“Book a call”) | Done |
| Borrowing power calculator (gated lead) | Done |
| Repayment calculator | Done |
| Stamp duty calculator | Done |
| Rate review form (`/rate-review`) | Done |
| Click-to-call (mobile sticky + tappable phone) | Done |
| Admin enquiries (leads from website) | Done |

Leads land in **Admin → Enquiries**. Follow-up today is manual (phone/email outside the system).

---

## What Phase 2 adds

### 2A — Client files + ticket / task system

**Problem:** Clients always have outstanding items (documents, signatures, lender conditions). Staff need one place to track and close them off.

**Solution:**

- **Convert enquiry → client file** when a lead becomes an active deal
- **Tasks/tickets** per client: title, description, owner (client or broker), status, due date, notes
- **Statuses:** Open → In progress → Done (closed)
- **Admin dashboard:** open tasks, overdue, filter by broker/client
- **Client file page:** enquiry history + all tasks + documents in one view

**Example tasks:**

| Task | Typical owner |
|------|----------------|
| Upload last 2 payslips | Client |
| Provide ID | Client |
| Sign privacy & credit guide | Client |
| Chase lender condition | Broker |
| Book valuation | Broker |

### 2B — DocuSign integration + signed document storage

**Problem:** Compliance and loan documents must be signed digitally and stored — not lost in email.

**Solution:**

- Send documents via **DocuSign** from admin (linked to client file)
- Client signs via email link (or embedded flow — TBC)
- **Webhook** when signed → store signed PDF + metadata on client file
- Optional: auto-close related task (e.g. “Sign privacy form”)

**Typical documents (confirm list with Kal):**

- Privacy consent  
- Credit guide acknowledgment  
- Authority to act / broker appointment  
- Additional lender/broker forms as needed  

**Business requirement:** Riskwisdom needs a **DocuSign subscription** with API access (paid plan). Templates and account setup are on the business side.

---

## End-to-end workflow (proposed)

```
Website lead (Phase 1)
        │
        ▼
Admin → Enquiries list → Broker contacts client
        │
        ▼
[2A] Convert to Client file
        │
        ├── Create tasks (what’s outstanding)
        │   Admin tracks → updates status → closes when done
        │
        ├── [2B] Send DocuSign when signature required
        │   Client signs → Signed PDF stored on client file
        │
        ▼
Tasks completed → loan progresses → file archived (optional later)
```

---

## Recommended build order

| Phase | Scope | Rationale |
|-------|--------|-----------|
| **2A first** | Client files + tickets | Works immediately; no third-party subscription required |
| **2B second** | DocuSign + storage | Depends on DocuSign account, templates, and API keys |
| **2C later** (optional) | Client upload portal, SMS/WhatsApp reminders, HubSpot sync | Separate agreement |

---

## Decisions needed from Kal

Please confirm or adjust:

1. **Tasks** — Are the examples above correct? Required fields: due date, priority, assigned broker?
2. **Client model** — One file per loan application, or one client with multiple loans over time?
3. **DocuSign** — Who creates the DocuSign account? Which plan (API-enabled)?
4. **Documents** — Which 3–5 documents go live in v1?
5. **Storage** — Signed PDFs in admin only for v1, or export/archive requirement from day one?
6. **Users** — How many admin/broker logins (Kal only, Kal + 1 admin, team)?
7. **Priority** — Start with 2A only, 2B only, or both in one delivery?

---

## Out of scope (Phase 2 unless agreed)

- Full CRM (HubSpot replacement)
- Client-facing portal for task upload (can be 2C)
- Automated SMS/WhatsApp pipeline (original Phase 1 item #7 — separate)
- Calendly bookings stored in admin (requires Calendly webhook / paid plan)

---

## Technical notes (for implementation after sign-off)

- Extends existing Laravel admin (`/admin`) and `Enquiry` model
- New models (proposed): `Client`, `Task`, `Document` (or equivalent)
- DocuSign: REST API + webhook endpoint for `envelope-completed`
- Signed files: `storage/app/clients/{id}/` or cloud (S3) if required
- `.env` keys: `DOCUSIGN_*` (integration key, account ID, etc.)

---

## Sign-off

| | Name | Date | Agree / Changes |
|---|------|------|-----------------|
| Client | Kal | | |
| Developer | Deepak | | |

**Reply format:** “Agree — start 2A” or list changes to Section 7.

---

## One-paragraph summary (for WhatsApp)

Phase 2 adds an admin **client file** with **trackable tasks** so staff can see and close outstanding items per client, plus **DocuSign** so compliance documents are signed digitally and **stored** against that file. Phase 1 already captures leads; Phase 2 runs the file after contact. Recommend building tasks first (2A), then DocuSign (2B), once Kal confirms scope and DocuSign account is ready.
