CREATE VIEW view_adminNavItemBreadcrumb
AS
SELECT
    A.nav_item_id,
    JSON_ARRAY(
        JSON_OBJECT('level', 1, 'name', X.nav_group_name, 'route', X.nav_group_route_start),
        IF(A.nav_group_route_start<>X.nav_group_route_start, JSON_OBJECT('level', 2, 'name', A.nav_group_name, 'route', A.nav_group_route_start),NULL),
        IF(A.nav_item_route<>A.nav_group_route_start, JSON_OBJECT('level', 3, 'name', A.nav_item_name, 'route', A.nav_item_route),NULL)
    ) AS nav_item_breadcrumb
FROM
    view_adminAppNavGroupNavItem A
    JOIN view_adminAppNavGroupNavItem X ON
        X.nav_group_route_start = A.app_route_start
