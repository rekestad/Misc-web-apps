CREATE VIEW view_adminAppNavGroupNavItem
AS
SELECT
    A.id                AS app_id,
    A.app_name          AS app_name,
    A.browser_title     AS app_browser_title,
    A.route_start       AS app_route_start,
    A.navbar_color      AS app_navbar_color,
    A.icon              AS app_icon,
    A.favicon           AS app_favicon,
    A.is_home_app       AS app_is_home_app,
    A.is_active         AS app_is_active,
    A.is_development    AS app_is_development,
    A.sort_order        AS app_sort_order,
    NG.id               AS nav_group_id,
    NG.nav_group_name   AS nav_group_name,
    NG.route_start      AS nav_group_route_start,
    NG.icon             AS nav_group_icon,
    NG.is_active        AS nav_group_is_active,
    NG.is_development   AS nav_group_is_development,
    NG.sort_order       AS nav_group_sort_order,
    NI.id               AS nav_item_id,
    NI.route            AS nav_item_route,
    NI.nav_item_name    AS nav_item_name,
    NI.sort_order       AS nav_item_sort_order
FROM
    admin_apps A
        LEFT JOIN admin_nav_groups NG ON NG.app_id = A.id
        LEFT JOIN admin_nav_items NI ON NI.nav_group_id = NG.id
