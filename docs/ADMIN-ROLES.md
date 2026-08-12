# Admin roles & staff permissions

## Roles

| Role | Who | Access |
|------|-----|--------|
| **Admin** | Company boss | Full panel access, manage users |
| **Staff** | Employees / callers | Only the permissions ticked for that person |

Both log in at `/admin/login`.

## How the boss adds more employees

1. Log in as Admin
2. Open **Users** in the sidebar
3. Click **Add staff**
4. Enter name, username, email, password
5. Role = **Staff**
6. Adjust **permission checkboxes** (Staff preset is pre-ticked)
7. Save

Repeat for every new hire — no code change needed.

## Permission check

- Controllers use middleware `admin.can:{permission}`
- Buttons are hidden when the user lacks that permission
- Staff can never receive `users.manage`

## Default Staff preset

Includes: view/export/convert enquiries, clients view/create/update, tasks view/manage, documents view/manage.

Does **not** include: delete enquiries/tasks/documents, archive clients, manage users.

## Book meeting (staff callers)

On **Enquiry** and **Client** pages, staff can:

1. **Book meeting** — opens Calendly with name / email / phone prefilled
2. **Copy booking link** — public `/book` URL to paste into SMS or WhatsApp

No extra permission is required beyond viewing the enquiry or client.

## Call tracking (staff callers)

On each **Enquiry** page, staff can:

1. Set **call status** — New, Called, Booked, Callback, No answer, Not interested
2. Add **call notes** — what was discussed and next step
3. Set **callback date** when status is Callback

**Enquiries filters:**

- **New** — not yet called
- **Callbacks due** — callback status with date today or overdue

Calendly bookings auto-set status to **Booked**.

**Caller preset permissions** (when adding staff): only `enquiries.view` — enough to view leads, update call tracking, call, and book meetings.

## Deploy note

After pulling this feature:

```bash
php artisan migrate --force
php artisan config:clear
php artisan config:cache
```

Existing admin users are set to role `admin` by migration.
