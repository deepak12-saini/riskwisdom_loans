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

## Deploy note

After pulling this feature:

```bash
php artisan migrate --force
php artisan config:clear
php artisan config:cache
```

Existing admin users are set to role `admin` by migration.
