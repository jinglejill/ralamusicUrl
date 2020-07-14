ALTER TABLE `mainproduct` ADD `Brand` VARCHAR(100) NOT NULL AFTER `ShortDescription`;
ALTER TABLE `mainproduct` ADD `Cost` FLOAT NOT NULL AFTER `Price`, ADD `Remark` VARCHAR(200) NOT NULL AFTER `Cost`;

CREATE TABLE `deleted` (
  `DeletedID` int(11) NOT NULL,
  `Json` text NOT NULL,
  `TableName` varchar(50) NOT NULL,
  `ModifiedUser` varchar(50) NOT NULL,
  `ModifiedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `shopeeproduct` ADD `CategoryID` INT NOT NULL AFTER `ItemSku`, ADD `AttributeID` INT NOT NULL AFTER `CategoryID`, ADD `AttributeValue` VARCHAR(50) NOT NULL AFTER `AttributeID`;
ALTER TABLE `shopeeproduct` ADD `AttributesJson` TEXT NOT NULL AFTER `CategoryID`;
ALTER TABLE `mainproduct` ADD `PrimaryCategory` INT NOT NULL AFTER `ShortDescription`;




ALTER TABLE `jdproduct` ADD `CategoryId` INT NOT NULL AFTER `OuterId`, ADD `BrandId` INT NOT NULL AFTER `CategoryId`;



SELECT * from  `mainproduct` left join lazadaproduct3 on mainproduct.Sku = lazadaproduct3.SellerSku WHERE ProductID > 143 and ProductID <= 500 and lazadaproduct3.SellerSku is not null;

UPDATE `mainproduct` left join lazadaproduct3 on mainproduct.Sku = lazadaproduct3.SellerSku set mainproduct.SpecialPrice = lazadaproduct3.special_price WHERE lazadaproduct3.SellerSku is not null and mainproduct.ProductID > 100 and mainproduct.ProductID <= 200;


UPDATE `mainproduct` left join lazadaproduct3 on mainproduct.Sku = lazadaproduct3.SellerSku set mainproduct.PrimaryCategory = lazadaproduct3.PrimaryCategory, mainproduct.brand = lazadaproduct3.brand WHERE lazadaproduct3.SellerSku is not null and mainproduct.ProductID>3430 and mainproduct.ProductID<=3500;
UPDATE `mainproduct` left join lazadaproduct3temp on mainproduct.Sku = lazadaproduct3temp.SellerSku set mainproduct.PrimaryCategory = lazadaproduct3temp.PrimaryCategory, mainproduct.brand = lazadaproduct3temp.brand WHERE lazadaproduct3temp.SellerSku is not null and mainproduct.ProductID>3430 and mainproduct.ProductID<=3500;

SELECT * from `mainproduct` left join lazadaproduct3 on mainproduct.Sku = lazadaproduct3.SellerSku WHERE lazadaproduct3.SellerSku is not null and mainproduct.ProductID>3430 and mainproduct.ProductID<=3500 ORDER BY `mainproduct`.`ProductID` ASC



UPDATE `mainproduct` left join lazadaproduct1temp on mainproduct.Sku = lazadaproduct1temp.SellerSku set mainproduct.PrimaryCategory = lazadaproduct1temp.PrimaryCategory, mainproduct.brand = lazadaproduct1temp.brand, mainproduct.SpecialPrice = lazadaproduct1temp.special_price WHERE lazadaproduct1temp.SellerSku is not null and mainproduct.ProductID>0 and mainproduct.ProductID<=500;
UPDATE `mainproduct` left join lazadaproduct2temp on mainproduct.Sku = lazadaproduct2temp.SellerSku set mainproduct.PrimaryCategory = lazadaproduct2temp.PrimaryCategory, mainproduct.brand = lazadaproduct2temp.brand, mainproduct.SpecialPrice = lazadaproduct2temp.special_price WHERE lazadaproduct2temp.SellerSku is not null and mainproduct.ProductID>0 and mainproduct.ProductID<=500;
UPDATE `mainproduct` left join lazadaproduct3temp on mainproduct.Sku = lazadaproduct3temp.SellerSku set mainproduct.PrimaryCategory = lazadaproduct3temp.PrimaryCategory, mainproduct.brand = lazadaproduct3temp.brand, mainproduct.SpecialPrice = lazadaproduct3temp.special_price WHERE lazadaproduct3temp.SellerSku is not null and mainproduct.ProductID>0 and mainproduct.ProductID<=500;

UPDATE `mainproduct` left join lazadaproduct3temp on mainproduct.Sku = lazadaproduct3temp.SellerSku set mainproduct.SpecialPrice = lazadaproduct3temp.special_price WHERE lazadaproduct3temp.SellerSku is not null and mainproduct.ProductID>0 and mainproduct.ProductID<=1500;
UPDATE `mainproduct` left join lazadaproduct3temp on mainproduct.Sku = lazadaproduct3temp.SellerSku set mainproduct.SpecialPrice = lazadaproduct3temp.special_price WHERE lazadaproduct3temp.SellerSku is not null and mainproduct.ProductID>3500 and mainproduct.ProductID<=5500;

------------------
create shopeeOrder 
ALTER TABLE `categorymapping` ADD `JdCategoryID` INT NOT NULL AFTER `ShopeeCategoryID`;

drop table categorymapping and import fresh one


SELECT DISTINCT mainproduct.PrimaryCategory,jdproduct.CategoryId,jdproduct.BrandId FROM mainproduct LEFT join jdproduct on mainproduct.Sku = jdproduct.Sku WHERE jdproduct.JdProductID is not null ORDER BY `mainproduct`.`PrimaryCategory` ASC


import categoryMappingWeb










