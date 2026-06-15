# STC Purchase & Order Flow — Complete System Documentation

> **Repository path:** `e:/xampp/htdocs/stc`  
> **Generated:** 2026-06-15  
> **Scope:** End-to-end purchase lifecycle — from supervisor site requisition through STC GLD dispatch, challan, and ledger, plus the reverse STC GLD outward flow and adhoc purchase management.

---

## Table of Contents

1. [System Overview & Actor Map](#1-system-overview--actor-map)
2. [Phase 1 — Inbound Purchase Flow (Site → STC GLD)](#2-phase-1--inbound-purchase-flow-site--stc-gld)
   - [Step 1 — Supervisor Requisition](#step-1--supervisor-requisition-stc_sub_agent47stc-requisitionphp)
   - [Step 2 — Manager Order Review & Approval](#step-2--manager-order-review--approval-stc_agent47order-managementphp)
   - [Step 3 — Procurement Combine & Place Order](#step-3--procurement-combine--place-order-stc_agent47procurement-managementphp)
   - [Step 4 — STC GLD Daily Requisition — Check & Dispatch](#step-4--stc-gld-daily-requisition--check--dispatch-stc_vikingsdaily-requisitionphp)
   - [Step 5 — Challan Generation](#step-5--challan-generation)
   - [Step 6 — Ledger](#step-6--ledger)
3. [Phase 2 — Outward Flow (STC GLD → Customer)](#3-phase-2--outward-flow-stc-gld--customer)
4. [Adhoc Purchase Management](#4-adhoc-purchase-management)
   - [Rack Management](#41-rack-management)
   - [Tools Track (PPA)](#42-tools-track-ppa)
   - [PPE Notes & PPE Tracker](#43-ppe-notes--ppe-tracker)
   - [Challan RCM Transfer Challan](#44-challan-rcm-transfer-challan)
5. [Item Status Reference](#5-item-status-reference)
6. [Item Type Reference](#6-item-type-reference)
7. [Unit Reference](#7-unit-reference)
8. [Database Table Reference](#8-database-table-reference)
9. [File & Directory Map](#9-file--directory-map)

---

## 1. System Overview & Actor Map

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         STC PURCHASE FLOW — OVERVIEW                        │
│                                                                               │
│  PHASE 1 (INBOUND) ─────────────────────────────────────────────────────►  │
│                                                                               │
│  [SUPERVISOR]     [MANAGER]       [PROCUREMENT]    [STC GLD / VIKINGS]      │
│  stc_sub_agent47  stc_agent47     stc_agent47       stc_vikings              │
│  stc-requisition  order-          procurement-      daily-requisition        │
│  .php             management.php  management.php    .php                     │
│       │                │                │                 │                  │
│  Creates          Reviews &        Combines          Receives PR,            │
│  requisition      approves items   multiple          checks adhoc,           │
│  per site/        per line;        approved          dispatches to           │
│  project          sets approved    items into a      site, issues            │
│                   qty; rejects     single PR;        challan                 │
│                   with reason      places order                              │
│                                                                               │
│  PHASE 2 (OUTWARD) ◄────────────────────────────────────────────────────── │
│                                                                               │
│  STC GLD creates requisition based on daily sale → Transfer Challan          │
│  → Customer delivery                                                          │
│                                                                               │
│  PURCHASE (ADHOC) ─────────────────────────────────────────────────────── │
│  Adhoc Table → Rack Management → Tools Track (PPA) → PPE Tracker             │
│  → Challan RCM Transfer Challan                                               │
└─────────────────────────────────────────────────────────────────────────────┘
```

### Actor Roles

| Actor | Directory | Page | Role |
|---|---|---|---|
| **Supervisor** | `stc_sub_agent47` | `stc-requisition.php` | Creates site-level requisitions; receives dispatched items |
| **Manager (Agent)** | `stc_agent47` | `order-management.php` | Reviews supervisor requisitions; approves/rejects items per line |
| **Procurement** | `stc_agent47` | `procurement-management.php` | Combines approved items from multiple managers; places consolidated PR to STC GLD |
| **STC GLD (Vikings)** | `stc_vikings` | `daily-requisition.php` | Receives PR; links product codes; dispatches from adhoc stock; manages rack; issues challan |
| **STC GLD (Agent Order)** | `stc_vikings` | `agent-order.php` | Manages outward orders to STC GLD's own customers with challan and ledger |

---

## 2. Phase 1 — Inbound Purchase Flow (Site → STC GLD)

### Overall Sequence Diagram

```
Supervisor ──► REQUISITION ──► Manager ──► APPROVE / REJECT
                                              │
                                     Approved items
                                              │
                                              ▼
                              Procurement ──► COMBINE into single PR
                                              │
                                         Place Order (PR# with reference)
                                              │
                                              ▼
                              STC GLD ──► Receive PR in Daily Requisition
                                              │
                                    Link Item Code from Adhoc catalog
                                              │
                                    Check Adhoc Balance (Rack)
                                              │
                                    Dispatch Balance to site
                                              │
                                    Generate Challan ──► Supervisor receives
                                              │
                                         Update Ledger
```

---

### Step 1 — Supervisor Requisition (`stc_sub_agent47/stc-requisition.php`)

#### Purpose
The Supervisor raises a material requisition on behalf of a site/project. Each requisition can contain multiple line items.

#### Creating a Requisition

1. Supervisor clicks **Add Requisition** — a modal (`bd-requisition-modal-lg`) opens.
2. Supervisor selects the **Site** (`load_cust_sup_site` — loaded from `nemesis/stc_agcart.php` → `load_cust_req_filter`).
3. For each item, the supervisor fills:

   | Field | Input | Notes |
   |---|---|---|
   | Item Description | Text input with autocomplete | `stc-sup-desc[]` — suggests from existing items if search > 3 chars via `stc_search_items` |
   | Quantity | Number | `stc-sup-qty[]` — must be > 0 |
   | Unit | Select | `stc-sup-unit[]` — see [Unit Reference](#7-unit-reference) |
   | Type | Select | `stc-sup-type[]` — Consumable, PPE, Supply, Tools & Tackles |
   | Priority | Select | `stc-sup-priority[]` — `1 = Normal`, `2 = Urgent` (urgent rows highlighted in red) |

4. Supervisor can add multiple items using **Add Another Item** button (clones the item card).
5. On submit, form data is posted to `nemesis/stc_agcart.php` with `stc-sup-hit` flag.
6. On success, cart is reset; the requisition list refreshes.

#### Viewing Requisitions

Supervisor can view existing requisitions via filters:

| Filter | Field | Notes |
|---|---|---|
| Date From | `stc-sup-req-beg-date` | Defaults to 7 days ago |
| Date To | `stc-sup-req-end-date` | Defaults to today |
| Project | `stc-sup-req-project-filter` | Loaded from `load_cust_req_filter` |
| Status | `stc-sup-req-status-filter` | All 9 statuses — see [Status Reference](#5-item-status-reference) |

Results load via `call_searched_requisition` (AJAX → `nemesis/stc_agcart.php`) with pagination (25 per page).

#### Receiving Dispatched Items

When items have been dispatched by STC GLD, the supervisor can:

1. Click **Receiver** button — opens `stc-sup-requisition-rece-modal`.
2. Enters **Quantity Received** (`stc-super-own-qnty-rec-text`).
3. Saves via `stc_rec_qntyhit` — validates entered qty against dispatched qty.
4. On confirmation, status updates and the supervisor's list refreshes.

#### Editing / Returning Items (before approval)

- **Edit item** (`.edit-req-item`): Updates item name, quantity, unit, type via `stc_req_edit_item_update`.
- **Remove item** (`.remove_from_purchase`): Deletes an item from the requisition via `stc_req_edit_item_delete` (only before approval).
- **Return item** (`.return-req-item`): Marks item as returned via `stc_req_return_item_show`.

#### Key AJAX Endpoints (`nemesis/stc_agcart.php`)

| Action | POST key | Description |
|---|---|---|
| Load project filter | `load_cust_req_filter` | Populates project dropdown |
| Search requisitions | `call_searched_requisition` | Returns paginated results HTML |
| Get requisition items | `get_requisition_pert` | Returns line items for a requisition |
| Submit requisition | `stc-sup-hit` | Creates requisition + items |
| Search item suggestions | `stc_search_items` | Returns JSON array of item name suggestions |
| Receive quantity | `stc_rec_qntyhit` | Records received quantity |
| Edit item | `stc_req_edit_item_update` | Updates item name/qty/unit/type |
| Delete item | `stc_req_edit_item_delete` | Removes item from requisition |
| Return item | `stc_req_return_item_show` | Marks item as returned |

---

### Step 2 — Manager Order Review & Approval (`stc_agent47/order-management.php`)

#### Purpose
The Manager (Agent) sees all supervisor requisitions for projects they manage or collaborate on. They review each line item, set an approved quantity, and either approve or reject it.

#### Authentication
Session-based (`stc_agent_id`, `stc_agent_name`, `stc_agent_role`). Cookie fallback for 7 days (`stc_agent_remember`). Redirects to `index.html` if unauthenticated.

#### Viewing Supervisor Requisitions

Query: `stc_cust_super_requisition_list_items` joined with `stc_cust_super_requisition_list`, `stc_cust_pro_supervisor`, `stc_cust_project`, `stc_cust_project_collaborate`, `stc_cust_pro_supervisor_collaborate`.

**Filter condition:** Items where:
- Project was created by this agent OR agent is a collaborator on the project
- Supervisor was created by this agent OR agent is a collaborator supervisor
- Requisition list status < 3 (not yet fully approved)
- Approved qty = 0 (still pending)

#### Table Columns

| Column | Source Field |
|---|---|
| Sl No | Auto-increment |
| Requisition ID & Date | `list_id`, `stc_cust_super_requisition_list_date` |
| Requisition For | `stc_cust_project_title` |
| Requisition From | `stc_cust_pro_supervisor_fullname` |
| Item Desc | `stc_cust_super_requisition_list_items_title` (clickable to open edit modal) |
| Unit | `stc_cust_super_requisition_list_items_unit` |
| Quantity | `stc_cust_super_requisition_list_items_reqqty` |
| Approve Quantity | Editable input `stc-sup-appr-qty{id}` |
| Remains Quantity | Calculated: `reqqty - approved_qty` |
| Status | Status badge (`Ordered` = blue) |
| Priority | Normal / **Urgent** (Urgent rows highlighted red `#ffa5a5`) |
| Type | `stc_cust_super_requisition_items_type` |
| ADD | `add_to_purchase` button (green plus icon) |
| REJECT | `remove_from_purchase` button (red ban icon) |

#### Manager Actions

**Approve Item** (`.add_to_purchase` → `stc_addtopurchase`):
1. Manager sets the **Approve Quantity** in the input.
2. Validates: `approvedQty > 0` AND `approvedQty <= reqqty`.
3. POSTs to `nemesis/stc_project.php` with `stc_addtopurchase:1`, `item_id`, `itemqty`, `itemstatus`.
4. On `success` response — row hides; item moves to status `Accepted` (visible to Procurement).

**Reject Item** (`.remove_from_purchase` → `stc_req_edit_item_delete`):
1. Manager clicks reject icon.
2. Hidden button `.rejectionmodalbtn` triggers `#stc-sup-requisition-rejection-modal`.
3. Manager must enter a **reason** (`reason-text`).
4. POSTs to `nemesis/stc_project.php` with `stc_req_edit_item_delete:1`, `req_id`, `list_id`, `reason`.
5. Alert: "Item Rejected Successfully." → page reloads.

**Edit Item Name / Priority** (`.stc-req-item-name-open-edit`):
1. Click on item name in the description column.
2. Modal `#stc-sup-requisition-item-edit-modal` opens pre-filled with `data-item-name` and `data-item-priority`.
3. Manager can change item name and priority (Normal / Urgent).
4. Save → POSTs `stc_req_edit_item_update:1` to `nemesis/stc_project.php`.

**Place Order** (`.placeorder` → `place_order`):
- An entire requisition can be placed as an order via `place_order`.
- Response `"no"` = remaining qty or item enable issue.
- On success, the requisition drawer closes.

**Set to Clean** (`.settoclean` → `clean_requisition`):
- Resets/clears a requisition.

#### Key AJAX Endpoints (`nemesis/stc_project.php`)

| Action | POST key | Description |
|---|---|---|
| View order items | `get_orders_pert` | Returns HTML for requisition line items |
| Set validate | `set_for_validate` | Updates item validation status |
| Place order | `place_order` | Moves requisition to order |
| Clean requisition | `clean_requisition` | Resets a requisition |
| Approve item | `stc_addtopurchase` | Approves item with qty → moves to Procurement |
| View requisition items | `get_req_orders_pert` | Returns items for a requisition group |
| Add to cart (line) | `go_for_req_sess` | Stores line in session cart |
| Remove from cart | `remove_from_req_sess` | Removes line from session cart |
| Place requisition | `place_requisition` | Submits a full requisition |
| Edit item name | `stc_req_edit_item_update` | Updates item name/priority |
| Reject item | `stc_req_edit_item_delete` | Rejects item with reason |

---

### Step 3 — Procurement Combine & Place Order (`stc_agent47/procurement-management.php`)

#### Purpose
Procurement sees all items that have been approved by managers (status = `Accepted`, list_status = 2). They select and combine items from multiple requisitions into a single consolidated **Purchase Requisition (PR)**, then place it as one order to STC GLD.

#### Authentication
Same session/cookie system as order-management.php.

#### Tab: Requisition

Query: `stc_cust_super_requisition_list_items` joined with `stc_cust_super_requisition_list`, `stc_cust_pro_supervisor`, `stc_cust_project`, `stc_agents` (approved by agent).

**Filter:** `stc_cust_super_requisition_list_status = '2'` (Accepted by manager).

Table columns match manager view, plus:
- **Approved By** (`stc_agents_name`)
- **Requis Remains Qty** (received qty subtracted from total)
- **Action**: Accept (green check) + Reject (red ban)

#### Procurement Actions on Requisition Tab

**Select All / Master Checkbox** (`.master-checkbox`):
- Selects all `.select-req` checkboxes in the table.

**Accept All** (`.acceptall` button):
- Clicks all checked `.add_to_accept_cart` buttons automatically.
- Deselects all checkboxes.
- Refreshes cart.

**Accept Item** (`.add_to_accept_cart` → `go_for_req_sess`):
1. Procurement sets approve qty in `stc-sup-appr-qty{id}` input.
2. Click green checkmark.
3. POSTs to `nemesis/stc_procurement.php` with `go_for_req_sess:1`, `item_id`, `item_req_id`, `itemqty`, `itemstatus=1`.
4. Cart refreshes automatically.

**Reject Item** (`.remove_from_purchase` → `stc_req_edit_item_delete`):
- Same rejection modal + reason workflow as manager step.
- Posts to `nemesis/stc_project.php`.

#### Tab: Cart

Cart is loaded via `call_reuisition_appr_cart_call:1` on page load and after every change.

Each requisition grouping in the cart can be:
- **Expanded** to view line items (`.ag-req-show-grid` → `get_req_orders_pert`).
- **Combined** (`.addtoreqcombinercart` → `requisition_combined`): Marks the requisition group for combining into a single outgoing PR.
- **Remove item from cart** (`.removreqitems` → `remove_req_sess_act`): Removes an individual item from session cart.
- **Remove entire requisition** (`.removerequisitionwithitems` → `remove_requisition`): Deletes a requisition block from the cart.

#### Placing the Combined Purchase Order

1. Procurement adds all desired items to the cart.
2. Enters a **Reference Number** (`stc-proc-place-order-refrenece` input).
3. Clicks **Place Order** (`.stc-proc-place-order-btn` → `place_req_sess_act:1`).
4. A **PR (Purchase Requisition)** record is created in the system with:
   - Combined items from multiple supervisors/projects
   - A reference number
   - Timestamp
5. STC GLD can now see this PR in `daily-requisition.php`.

#### Key AJAX Endpoints (`nemesis/stc_procurement.php`)

| Action | POST key | Description |
|---|---|---|
| Load cart | `call_reuisition_appr_cart_call` | Returns HTML for current cart content |
| View requisition items | `get_req_orders_pert` | Returns items for a group |
| Accept item to cart | `go_for_req_sess` | Adds item to session-based cart |
| Remove cart item | `remove_req_sess_act` | Removes item from cart |
| Combine requisition | `requisition_combined` | Marks requisition for combining |
| Place order | `place_req_sess_act` | Creates combined PR → STC GLD |
| Remove requisition | `remove_requisition` | Removes requisition block from cart |

---

### Step 4 — STC GLD Daily Requisition — Check & Dispatch (`stc_vikings/daily-requisition.php`)

#### Purpose
STC GLD (Vikings) receives all PRs placed by Procurement. They check available stock in the Adhoc inventory, link product codes, dispatch from Adhoc racks, and track status per line item.

#### Authentication
`STCAuthHelper::checkAuth()` + role check via `kattegat/role_check.php` (page_code = 601).

#### Viewing Requisitions

Loaded via AJAX: `kattegat/ragnar_order.php` → `stc_call_daily_requisitions`

Filters available:

| Filter | Field | Notes |
|---|---|---|
| Date From | `#dr-datefrom` | Pre-filled: 7 days ago |
| Date To | `#dr-dateto` | Pre-filled: today |
| PR Name | `#dr-pr-name` | Searchable combobox — loaded from `stc_call_daily_requisition_pr_names` |
| Search | `#dr-search` | Free text — project, supervisor, req#, item description |

Results are paginated (25 per page). Pagination uses separate count request (`stc_call_daily_requisition_count`) for performance.

#### Table Columns

| Column | Description |
|---|---|
| Sl No | Serial number |
| PR Name / PR No / Date | Combined PR reference + ID + timestamp (links to `stc-requisition-combiner-fsale.php?requi_id=...`) |
| Project Name & Managers | Project title + manager name |
| Requisition Sent By | Supervisor name, contact, Req#, date/time |
| Item Desc | Description of requisitioned item |
| Unit | Unit of measure |
| Req Qty | Originally requested quantity |
| Dispatched Qty | Quantity already dispatched from Adhoc |
| Item Code / Balance Qty / Rack | Linked product code from Adhoc + remaining balance + rack location |
| Status | Color-coded status badge |
| Item Type | Consumable / PPE / Supply / Tools & Tackles |
| Action | Edit (Adhoc Balance) / Dispatch lines view |
| Log | View status change logs |

#### Adhoc Balance Modal (`#dailyReqBalanceModal`)

Opened via `.dr-balance-btn` → loads `stc_call_daily_requisition_balance:1`.

Shows a summary of the requisition line at the top:
- Item name, quantity, unit required

The balance table shows:

| Column | Description |
|---|---|
| Item Code | Product ID from Adhoc catalog |
| Item Description | Product name |
| Requisition Balance Qty | Remaining qty to dispatch (req - dispatched). Editable via `.dr-edit-qtyunit` link |
| Adhoc Balance | Current stock available in Adhoc for this product |
| Rack | Adhoc PPA rack selector (dropdown from `adhoc_options`) |
| Adjust Quantity | Editable qty for dispatch (`dr-rack-input`) |
| Tools Track (PPA) | For "Tools & Tackles" — PPA tool selector + Send button |
| Action | Dispatch Balance + Change Item Code buttons |

**Unit mismatch warning**: If item unit ≠ product unit, Dispatch button is hidden and a red warning appears prompting to change product or update requisition unit.

#### Adding / Changing Item Code

When no product is linked yet:
1. Button **Add Item Code** (`.dr-show-itemcode-form`) appears.
2. A product search form opens with:
   - **By Category** (`dr-filterbycat` — populated via `call_cat`)
   - **By Sub Category** (`dr-filterbysubcat` — populated via `call_sub_cat`)
   - **By Name** (free text `dr-searchbystcname`)
3. Click **Search** → `stc_dr_filter_products` returns product cards.
4. Click **Select** on a product card → `stc_update_daily_requisition_item_code:1` with `item_id`, `product_id`, `old_product_id`.
5. Item code is now linked; balance modal reloads.

**Change Item Code** (`.dr-change-itemcode-btn`): Same product search but replaces the existing linked product.

#### Edit Qty / Unit / Type (`.dr-edit-qtyunit`)

Clicking the Requisition Balance Qty link opens an inline editor in that cell:
- **Qty** input (number)
- **Unit** dropdown
- **Item Type** dropdown (Consumable / PPE / Supply / Tools & Tackles)
- **Update** → POSTs `stc_update_daily_requisition_qty_unit:1` with `item_id`, `product_id`, `pending_qty`, `unit`, `item_type`.
- **Cancel** → Restores original cell HTML.

#### Dispatching from Adhoc

1. Verify adhoc balance is available.
2. Select **Adhoc PPA** from the rack dropdown (`dr-adhoc-select`).
3. Enter **Adjust Quantity** (`dr-rack-input`).
4. Click **Dispatch Balance** (`.dr-dispatch-balance-btn`).
5. SweetAlert2 confirmation dialog appears.
6. On confirm → POSTs `stc_dispatch_daily_requisition_balance:1` with `item_id`, `product_id`, `dispatch_qty`, `adhoc_id`.
7. On success:
   - Row balance updates in real-time (new balance shown).
   - If balance reaches 0, row slides up and disappears.
   - Dispatched Qty column in main table updates.
   - Status moves toward **Dispatched** (status 4).

#### Pending Status

When Adhoc balance is insufficient:
1. **Update Pending** button (`.dr-update-pending-inline`) + reason text input appear.
2. GLD staff enter the reason and click Update Pending.
3. POSTs `update_requisition_status:1` with `id`, `status=9`, `remarks`.
4. Status updates to **Pending** (bright red badge).

Alternatively from the status change modal (`#statusRemarkModal`):
- `.btn-change-status` opens the modal with a remarks textarea.
- Save → same `update_requisition_status` call.

#### Dispatched Lines Modal (`#drDispatchLinesModal`)

Opened via `.dr-dispatch-lines-open-btn` (truck icon) — visible only when dispatched qty > 0.

Shows all individual dispatch events for a requisition line:

| Column | Description |
|---|---|
| Adhoc ID | The Adhoc PPA ID from which stock was taken |
| Product | Product code + name + unit |
| Rack | Rack name/location |
| Qty | Dispatched quantity (editable if not verified) |
| Date | Dispatch date |
| Action | Save (edit qty) / Remove line |

**Editing dispatch line** (`.dr-dispatch-line-save-qty` → `stc_dr_update_dispatch_line_qty`):
- Allowed only if the requisition is **not** verified.
- If verified, a warning shows: *"This requisition line is verified on Verify Dispatch. Quantities cannot be edited."*

**Removing a dispatch line** (`.dr-dispatch-line-remove` → `stc_dr_remove_dispatch_line`):
- SweetAlert2 confirmation.
- On confirm, record is deleted and list + main table refresh.

#### Tools Track (PPA) — Tools & Tackles Items

For items of type **Tools & Tackles**:
1. After dispatching, a **PPA tool selector** dropdown appears in the Tools Track column.
   - Options populated from `tools_track_options` (PPA / unique ID entries).
   - Hint shown: *"After dispatch, pick the PPA tool below and click Send to record it in Tools Track with this requisition."*
2. Select PPA tool → click **Send** (`.dr-copy-tool-track-btn`).
3. POSTs `stc_dr_copy_tool_track:1` with `item_id`, `product_id`, `toolsdetails_id`.
4. Records the tool dispatch in `stc_tooldetails_track` table.

If no tool rows exist (no PPA entries created during purchase), warning shown: *"No tool rows — Add tool on purchase (PPA) first."*

#### Logs Modal (`#dailyReqLogsModal`)

- Button appears only when `logs_count > 0`.
- Click **View (n)** → POSTs `stc_call_daily_requisition_logs:1` with `item_id`.
- Returns timestamped log entries showing all status changes with title, message, and date.

#### Key AJAX Endpoints (`kattegat/ragnar_order.php`)

| Action | POST key | Description |
|---|---|---|
| Load requisitions | `stc_call_daily_requisitions` | Paginated JSON list |
| Count for pagination | `stc_call_daily_requisition_count` | Returns total pages |
| Load PR names | `stc_call_daily_requisition_pr_names` | Returns all PR reference names |
| Load logs | `stc_call_daily_requisition_logs` | Returns log entries for an item |
| Load balance | `stc_call_daily_requisition_balance` | Returns linked product + adhoc balance |
| Load dispatch lines | `stc_call_daily_requisition_dispatch_lines` | Returns per-item dispatch history |
| Search products | `stc_dr_filter_products` | Returns product cards HTML |
| Add item code | `stc_update_daily_requisition_item_code` | Links product to requisition item |
| Edit qty/unit/type | `stc_update_daily_requisition_qty_unit` | Updates balance qty, unit, type |
| Dispatch balance | `stc_dispatch_daily_requisition_balance` | Dispatches stock from Adhoc |
| Update dispatch line qty | `stc_dr_update_dispatch_line_qty` | Edits a single dispatch record |
| Remove dispatch line | `stc_dr_remove_dispatch_line` | Deletes a dispatch record |
| Update status (pending) | `update_requisition_status` | Sets item to Pending with remarks |
| Copy to Tools Track | `stc_dr_copy_tool_track` | Records tool dispatch in stc_tooldetails_track |

---

### Step 5 — Challan Generation

Accessed from the Daily Requisition page via **Challan** button linking to `verify-challan.php`.

Role-restricted: Visible to specific employee IDs (e.g., empl_id = 20 or 1 can access Verify button → `verify.php`).

**Verify Dispatch (`verify.php`):**
- Used to mark a requisition line as **verified** after physical dispatch confirmation.
- Once verified, dispatch line quantities cannot be edited or removed in the Dispatched Lines modal.
- Verified status is checked via `response.verified === 1`.

**Challan (`verify-challan.php`):**
- Generates a delivery challan document for the dispatched items.
- Linked per PR: each PR produces one challan document covering all dispatched line items for that requisition batch.
- Challan includes: PR reference, project, supervisor, item list, dispatched quantities, rack/adhoc source, date.

---

### Step 6 — Ledger

The **PR (combined requisition)** page `stc-requisition-combiner-fsale.php?requi_id=...` (linked from PR Name column in Daily Requisition) serves as the ledger entry for each purchase cycle:
- Shows all supervisor requisitions combined into the PR.
- Tracks dispatched vs. remaining quantities.
- Forms the basis for financial ledger reconciliation against purchase invoices from adhoc.

---

## 3. Phase 2 — Outward Flow (STC GLD → Customer)

This flow is managed in `stc_vikings/agent-order.php`.

### Flow Overview

```
STC GLD Daily Sale Analysis
         │
         ▼
  Create Requisition (internal stock need based on daily sale)
         │
         ▼
  Transfer Challan (internal stock movement document)
         │
         ▼
  Create Customer Order on STC GLD
         │
         ▼
  Customer Delivery + Challan
         │
         ▼
  Ledger Update
```

### Key Features

- STC GLD acts as a distributor/supplier to its own customers.
- Orders are placed based on **daily sale patterns** — not just ad hoc requests.
- A **Transfer Challan** is generated for internal stock movements from the warehouse/adhoc to the dispatch point.
- Each customer order generates a challan.
- All transactions flow into the ledger for reconciliation.

---

## 4. Adhoc Purchase Management

The Adhoc system is the central inventory/warehouse for STC GLD from which all dispatches originate.

### 4.1 Rack Management

- Adhoc stock is physically stored in **Racks** (rack names/IDs maintained in the system).
- When dispatching, GLD staff select the specific **Adhoc PPA** (Purchase/Procurement Advice) and rack.
- The `adhoc_options` array returned by the balance API lists available PPAs with their rack labels.
- `dr-adhoc-select` dropdown in the Balance modal lets staff choose which Adhoc PPA / rack to dispatch from.
- Rack names appear in the dispatched lines table (column: Rack) for traceability.
- **Balance tracking**: Each Adhoc PPA has a balance quantity. After dispatch, balance decreases. When balance reaches 0, it is removed from the active dispatch list.

### 4.2 Tools Track (PPA)

Tools & Tackles items have a dedicated tracking layer beyond normal stock dispatch.

**What is PPA?**
- PPA = **Purchase/Procurement Advice** (the purchase record in the adhoc table).
- Each PPA entry corresponds to a purchase event: an item was physically purchased and stocked.
- For tools, each PPA can have a unique tool ID (serial number or asset code) tracked in `stc_tooldetails_track`.

**Tools Track Flow:**

```
Purchase created in Adhoc (PPA record created)
         │
         ▼
Tool added to PPA with unique ID / serial number
         │
         ▼
Requisition item dispatched from Adhoc (stc_dispatch_daily_requisition_balance)
         │
         ▼
Tool Track step: Select PPA tool from dropdown → Click Send
         │
         ▼
Record created in stc_tooldetails_track:
  - Links item_id (requisition line)
  - Links product_id (Adhoc product)
  - Links toolsdetails_id (specific PPA tool row)
  - Records which requisition this tool was dispatched for
         │
         ▼
Full traceability: which tool (by serial/PPA unique ID) went to which site
```

**API:** `stc_dr_copy_tool_track:1` → `kattegat/ragnar_order.php`

**Prerequisite:** PPA entry must be created with tool rows at purchase time. If not, warning: *"No tool rows — Add tool on purchase (PPA) first."*

### 4.3 PPE Notes & PPE Tracker

Items of type **PPE (Personal Protective Equipment)** follow a similar pattern to Tools Track:

- PPE items are tracked per-issue (who received which PPE, when, and for which site/project).
- **PPE Notes** are recorded at the point of dispatch, capturing:
  - PPE item description
  - Quantity issued
  - Recipient (supervisor / site)
  - Date of issue
  - Adhoc PPA reference
- **PPE Tracker** maintains a running log of all PPE issuances, allowing audits and balance checks of PPE stock per site.

The PPE workflow is analogous to Tools Track:
1. PPE purchased → Adhoc PPA entry created.
2. Requisition dispatched.
3. PPE note recorded against the dispatch.
4. PPE Tracker updated.

### 4.4 Challan RCM Transfer Challan

**RCM (Receipt Confirmation / Reverse Charge Mechanism) Transfer Challan** is generated for internal movements of goods:

```
Stock in Adhoc Warehouse
         │
         ▼
Transfer Challan created (RCM)
  - From: Adhoc warehouse / rack
  - To: Delivery point / customer site
  - Items: product code, description, qty, unit
  - Reference: Adhoc PPA ID
  - Date & authorized signatory
         │
         ▼
Physical goods moved with challan as document
         │
         ▼
Challan verified at destination → Verify Dispatch (`verify.php`)
         │
         ▼
Ledger updated: stock out recorded against PPA
```

Challan documents are accessible via:
- `verify-challan.php` — main challan viewer/generator
- `stc-requisition-combiner-fsale.php` — PR-level ledger/challan for combined requisitions

---

## 5. Item Status Reference

All requisition line items pass through these statuses (stored as integer codes):

| Code | Label | Badge Color | Description |
|---|---|---|---|
| `1` | **Ordered** | Blue `#3498db` | Supervisor has placed the requisition; awaiting manager review |
| `2` | **Approved** | Green `#2ecc71` | Manager has set approved qty; awaiting procurement |
| `3` | **Accepted** | Dark Green `#27ae60` | Procurement has accepted the item into a PR |
| `4` | **Dispatched** | Orange `#f39c12` | STC GLD has dispatched from Adhoc stock |
| `5` | **Received** | Teal `#16a085` | Supervisor has confirmed receipt at site |
| `6` | **Rejected** | Red `#e74c3c` | Item rejected by manager or procurement (with reason) |
| `7` | **Canceled** | Grey `#95a5a6` | Requisition or item canceled |
| `8` | **Returned** | Purple `#9b59b6` | Item returned by supervisor |
| `9` | **Pending** | Bright Red `rgb(255,47,47)` | GLD marked pending due to insufficient Adhoc stock (with remarks) |

**Requisition List Status** (header-level, not line-item):

| Code | Label | Meaning |
|---|---|---|
| `1` | Ordered | Submitted by supervisor; awaiting manager |
| `2` | Accepted | Manager has reviewed and accepted items |
| `3` | Approved | Procurement has approved and placed order |

---

## 6. Item Type Reference

| Value | Label | Tracking |
|---|---|---|
| `Consumable` | CONSUMABLE | Standard dispatch; no special tracker |
| `PPE` | PPE | PPE Notes + PPE Tracker on dispatch |
| `Supply` | SUPPLY | Standard dispatch |
| `Tools & Tackles` | TOOLS & TACKLES | Tools Track (PPA) required on dispatch |

---

## 7. Unit Reference

All units used in requisitions and dispatches:

| Unit | Description |
|---|---|
| BAG | Bag |
| BOTTLE | Bottle |
| BOX | Box |
| BUNDLE | Bundle |
| CASE | Case |
| CBM | Cubic Metre |
| CFT | Cubic Feet |
| COIL | Coil |
| FEET | Feet |
| JAR | Jar |
| KGS | Kilograms |
| LOT | Lot |
| LTR | Litre |
| MTR | Metre |
| MTS | Metric Tonnes |
| NOS | Numbers (pieces) |
| PAIR | Pair |
| PKT | Packet |
| ROLL | Roll |
| SET | Set |
| SQFT | Square Feet |
| SQMT | Square Metre |

---

## 8. Database Table Reference

| Table | Used In | Description |
|---|---|---|
| `stc_cust_super_requisition` | order-management | Old requisition header (supervisor) |
| `stc_cust_super_requisition_list` | All phases | Requisition list header (new structure) |
| `stc_cust_super_requisition_list_items` | All phases | Line items per requisition list |
| `stc_cust_super_requisition_list_items_rec` | procurement-mgmt | Received quantity records per item |
| `stc_cust_pro_supervisor` | All phases | Supervisor records |
| `stc_cust_pro_supervisor_collaborate` | order-management | Supervisor collaboration (agent teams) |
| `stc_cust_project` | All phases | Project records |
| `stc_cust_project_collaborate` | order-management | Project team collaboration |
| `stc_agents` | All phases | Manager/agent records |
| `stc_tooldetails_track` | daily-requisition | Tools & Tackles dispatch tracker |
| `stc_cust_super_requisition_list_items_acceptby` | procurement-mgmt | Records who approved each item |
| Adhoc table | daily-requisition | Warehouse stock (product, PPA, balance, rack) |

---

## 9. File & Directory Map

```
e:/xampp/htdocs/stc/
│
├── stc_sub_agent47/                     ← SUPERVISOR portal
│   ├── stc-requisition.php              ← Requisition create & view (Phase 1, Step 1)
│   ├── header-nav.php
│   ├── sidebar-nav.php
│   └── nemesis/
│       └── stc_agcart.php               ← AJAX handler: requisition CRUD, cart
│
├── stc_agent47/                         ← MANAGER + PROCUREMENT portal
│   ├── order-management.php             ← Manager review & approve (Phase 1, Step 2)
│   ├── procurement-management.php       ← Procurement combine & order (Phase 1, Step 3)
│   ├── header-nav.php
│   ├── sidebar-nav.php
│   ├── ui-setting.php
│   └── nemesis/
│       ├── stc_project.php              ← AJAX: manager approve/reject, place order
│       └── stc_procurement.php          ← AJAX: procurement cart, combine, place PR
│
├── stc_vikings/                         ← STC GLD portal
│   ├── daily-requisition.php            ← GLD receive PR, dispatch (Phase 1, Step 4)
│   ├── agent-order.php                  ← GLD outward orders to customers (Phase 2)
│   ├── verify.php                       ← Verify dispatch (challan verification)
│   ├── verify-challan.php               ← Challan generation & viewer
│   ├── stc-requisition-combiner-fsale.php ← PR ledger / combined requisition view
│   ├── header-nav.php
│   ├── sidebar-nav.php
│   └── kattegat/
│       ├── auth_helper.php              ← STCAuthHelper — authentication
│       ├── role_check.php               ← Page-level role permission check
│       └── ragnar_order.php             ← AJAX: GLD daily req, balance, dispatch, logs
│
└── MCU/
    └── db.php                           ← Shared database connection
```

---

## Appendix — Full End-to-End Status Journey of a Single Item

```
SUPERVISOR submits item
    └─► status = 1 (Ordered)
           │
    MANAGER reviews
           ├─► REJECT → status = 6 (Rejected) ──────────────────── END
           └─► APPROVE (set qty) → status = 2 (Approved)
                    │
           PROCUREMENT reviews
                    ├─► REJECT → status = 6 (Rejected) ──────────── END
                    └─► ACCEPT + COMBINE into PR → status = 3 (Accepted)
                             │
                    STC GLD receives PR
                             │
                        Link Item Code (Adhoc product)
                             │
                        Check Adhoc Balance
                             ├─► No stock → status = 9 (Pending) + remarks
                             │         │
                             │    Stock arrives → balance added to Adhoc
                             │         │
                             └─► Stock available → Dispatch from Adhoc
                                      │
                                 status = 4 (Dispatched)
                                      │
                                 [If Tools & Tackles]
                                 Record in Tools Track (PPA)
                                      │
                                 [If PPE]
                                 Record PPE Note + PPE Tracker
                                      │
                                 Generate Challan
                                      │
                                 Verify Dispatch (verify.php)
                                      │
                             SUPERVISOR receives item at site
                                      │
                                 Enter received qty
                                      │
                                 status = 5 (Received)
                                      │
                                 [Item may be returned]
                                 status = 8 (Returned)
                                      │
                                 Ledger updated ─────────────── END
```

---

*Document generated from source analysis of:*
- `stc_sub_agent47/stc-requisition.php` (951 lines)
- `stc_agent47/order-management.php` (757 lines)
- `stc_agent47/procurement-management.php` (644 lines)
- `stc_vikings/daily-requisition.php` (1498 lines)
- `stc_vikings/agent-order.php` (4032 lines)
