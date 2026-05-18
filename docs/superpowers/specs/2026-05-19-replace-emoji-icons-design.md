# Emoji to SVG Icon Replacement Design

## Purpose
Replace all emoji icons across the project with professional inline SVG icons to ensure visual consistency and allow for styling via CSS. Provide a scalable way to use icons in the future without relying on external CDNs or emojis.

## Architecture & Approach

### 1. Icon Helper Function
A new function `kg_icon($name, $class = '')` will be added to `functions.php`. 
This function acts as an inline SVG registry. It will return the SVG string for a given icon name based on Heroicons (24x24 outline). 
If an unknown icon name is provided, it will return an empty string or a default placeholder.

**Supported Initial Icons:**
- `search` (🔍)
- `briefcase` (💼)
- `location` (📍)
- `building` (🏢)
- `crown` (👑)
- `clipboard` (📋)
- `refresh` (🔄)

### 2. Front-End Files
We will update the following front-end files to use `kg_icon()` instead of emojis:
- `our-jobs.php`: `🔍` -> `kg_icon('search')`, `💼` -> `kg_icon('briefcase')`
- `search.php`: `🔍` -> `kg_icon('search')`
- `single-jobs.php`: `📍` -> `kg_icon('location')`, `🏢` -> `kg_icon('building')`
- `inc/ats-dashboard.php`: (Admin HTML dashboard rendering) `👑`, `📋`, `🔄`, `💼` -> `kg_icon()`

### 3. Backend/Data Files (Constraint Handling)
The backend CPT (Custom Post Type) definition files use emojis inside PHP arrays for WordPress statuses and dropdown `<option>` tags. HTML dropdowns do not support rendering SVGs.
To resolve this, we will remove the emojis entirely from these strings, leaving clean text.
Files affected:
- `inc/cpt-applications.php`
- `inc/cpt-inquiries.php`
- `kings/inc/cpt-applications.php`
- `kings/inc/cpt-inquiries.php`

## Success Criteria
- No emojis acting as icons remain in the codebase.
- The front-end renders SVGs correctly with current sizing/styling.
- The `kg_icon()` function is available for future use.
- The backend dropdowns render clean text without breaking form submission values.
