/**
 * Author:  ahmad
 * Created: Jul 18, 2017
 */

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_category_childs`(IN `p_parent_id` INT)
BEGIN
    SELECT *, 1 AS `child_type` FROM `categories` WHERE parent_id = p_parent_id;
    SELECT *, 2 AS `child_type` FROM `products`  WHERE parent_id = p_parent_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_category_path`(IN `p_category_id` INT)
BEGIN

    DECLARE d_title_ar VARCHAR(255);
    DECLARE d_title_en VARCHAR(255);
    DECLARE d_child_type INT;
    DECLARE d_parent_id INT;

    DECLARE  cur_category CURSOR FOR SELECT `category_id`, `title_ar`, `title_en`, 1 AS `child_type`, `parent_id` FROM `categories` WHERE category_id = p_category_id;
    #DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_more_rows = TRUE;

    CREATE TEMPORARY TABLE `category_temp` (
      `category_id` int(11),
      `title_ar`    varchar(255),
      `title_en`    varchar(255),
      `child_type`  tinyint(1),
      `parent_id`   int(11)
    );

    loop_label:  LOOP
        IF  p_category_id > 0 THEN

            OPEN  cur_category;
            FETCH cur_category INTO p_category_id, d_title_ar, d_title_en, d_child_type, d_parent_id;
            CLOSE cur_category;

            INSERT INTO `category_temp` (`category_id`, `title_ar`, `title_en`, `child_type`, `parent_id`)
                                                    VALUES ( p_category_id, d_title_ar, d_title_en, d_child_type, d_parent_id );

            SET p_category_id = d_parent_id;

            ITERATE loop_label;
        ELSE
            LEAVE   loop_label;
        END  IF;
    END LOOP;

    SELECT * FROM `category_temp` WHERE 1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_section_childs`(IN `p_parent_id` INT)
BEGIN
SELECT *, 1 AS `child_type` FROM `sections` WHERE parent_id = p_parent_id;
SELECT *, 2 AS `child_type` FROM `targets`  WHERE parent_id = p_parent_id;
SELECT *, 3 AS `child_type` FROM `embeds`   WHERE parent_id = p_parent_id;
SELECT *, 4 AS `child_type` FROM `links`    WHERE parent_id = p_parent_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_section_path`(IN `p_section_id` INT)
    NO SQL
BEGIN

    DECLARE d_title_ar VARCHAR(255);
    DECLARE d_title_en VARCHAR(255);
    DECLARE d_child_type INT;
    DECLARE d_parent_id INT;

    DECLARE  cur_section CURSOR FOR SELECT `section_id`, `title_ar`, `title_en`, 1 AS `child_type`, `parent_id` FROM `sections` WHERE section_id = p_section_id;

    CREATE TEMPORARY TABLE `sections_temp` (
      `section_id` int(11),
      `title_ar`    varchar(255),
      `title_en`    varchar(255),
      `child_type`  tinyint(1),
      `parent_id`   int(11)
    );

    loop_label:  LOOP
        IF  p_section_id > 0 THEN

            OPEN  cur_section;
            FETCH cur_section INTO p_section_id, d_title_ar, d_title_en, d_child_type, d_parent_id;
            CLOSE cur_section;

            INSERT INTO `sections_temp` (`section_id`, `title_ar`, `title_en`, `child_type`, `parent_id`)
                                VALUES ( p_section_id, d_title_ar, d_title_en, d_child_type, d_parent_id );

            SET p_section_id = d_parent_id;

            ITERATE loop_label;
        ELSE
            LEAVE   loop_label;
        END  IF;
    END LOOP;

    SELECT * FROM `sections_temp` WHERE 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_sub_categories`(IN `p_category_id` INT)
BEGIN

  SET max_sp_recursion_depth := 255;

  DROP TABLE IF EXISTS `sub_categories_temp`;

  CREATE TABLE IF NOT EXISTS `sub_categories_temp` (
    `category_id` int(11),
  `parent_id`   int(11)
  );

  CALL _add_sub_categories(p_category_id);


  SELECT * FROM `sub_categories_temp` WHERE 1 ORDER BY `parent_id`;
  DROP TABLE `sub_categories_temp`;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `search_category_childs`(IN `p_search_item` VARCHAR(255) CHARSET 'utf8')
BEGIN
    SELECT *, 1 AS `child_type` FROM `category` WHERE ( `title_ar` LIKE p_search_item OR `title_en` LIKE p_search_item );
    SELECT *, 2 AS `child_type` FROM `product`  WHERE ( `title_ar` LIKE p_search_item OR `title_en` LIKE p_search_item );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `search_section_childs`(IN `p_search_item` VARCHAR(255) CHARSET utf8)
BEGIN
    SELECT *, 1 AS `child_type` FROM `sections` WHERE ( `title_ar` LIKE p_search_item OR `title_en` LIKE p_search_item );
    SELECT *, 2 AS `child_type` FROM `targets`  WHERE ( `title_ar` LIKE p_search_item OR `title_en` LIKE p_search_item );
    SELECT *, 3 AS `child_type` FROM `embeds`   WHERE ( `title_ar` LIKE p_search_item OR `title_en` LIKE p_search_item );
    SELECT *, 4 AS `child_type` FROM `links`    WHERE ( `title_ar` LIKE p_search_item OR `title_en` LIKE p_search_item );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `test`(IN `p_start` INT, IN `p_count` INT)
BEGIN
SET @temp1=CONCAT('SELECT * FROM `sections` WHERE 1 LIMIT ',p_start,', ',p_count,'');
PREPARE stmt1 FROM @temp1;
EXECUTE stmt1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `_add_sub_categories`(IN `p_category_id` INT)
BEGIN


  DECLARE finished INT DEFAULT FALSE;

  DECLARE d_category_id INT DEFAULT 0;
  DECLARE d_parent_id   INT DEFAULT 0;

  DECLARE cur_category CURSOR FOR SELECT `category_id`, `parent_id` FROM `categories` WHERE `parent_id` = p_category_id;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = TRUE;

  /*SELECT concat("SELECT `category_id` FROM `categories` WHERE `parent_id` = ", p_category_id) AS '**INFO**';*/

  OPEN  cur_category;

  fetch_label:  LOOP

  FETCH cur_category INTO d_category_id, d_parent_id;

  IF finished THEN
    LEAVE fetch_label;
  ELSE

        INSERT INTO `sub_categories_temp` (`category_id`, `parent_id`)
                  VALUES ( d_category_id, d_parent_id );

        SET p_category_id = d_category_id;

    /*SELECT concat("`category_id` = ", d_category_id) AS '**INFO**';*/

    CALL _add_sub_categories(d_category_id);

        ITERATE fetch_label;

    END IF;

  END LOOP;

  CLOSE cur_category;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `_debug_msg`(IN `msg` VARCHAR(255))
BEGIN
    SELECT concat("****** ", msg) AS '** DEBUG **';
END$$

DELIMITER ;