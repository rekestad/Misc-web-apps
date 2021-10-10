CREATE VIEW view_ingredients AS
SELECT
    `I`.`id`                               AS `id`,
    `I`.`ingredient_name`                  AS `ingredient_name`,
    `UT`.`unit_type_name`                  AS `unit_type_name`,
    `UT`.`unit_type_abbr`                  AS `unit_type_abbr`,
    `C`.`category_name`                    AS `category_name`,
    (SELECT
         GROUP_CONCAT(DISTINCT CONCAT(`D`.`dish_name`, ' (', FORMAT(`DI`.`quantity`, 1), ')') ORDER BY
                      `D`.`dish_name` ASC SEPARATOR '¤')
     FROM
         (`fp_dishes` `D`
             JOIN `fp_dish_ingredient` `DI` ON ((`DI`.`dish_id` = `D`.`id`)))
     WHERE
         (`DI`.`ingredient_id` = `I`.`id`)) AS `ingredient_dishes`
FROM
    ((`fp_ingredients` `I` JOIN `fp_unit_types_fl` `UT` ON ((`UT`.`id` = `I`.`unit_type_id`)))
        JOIN `fp_ingredient_categories_fl` `C` ON ((`C`.`id` = `I`.`category_id`)))
