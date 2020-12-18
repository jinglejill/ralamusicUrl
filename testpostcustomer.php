select * from postcustomernotuse where Telephone in (select Telephone from (SELECT Telephone, count(*) Count FROM `postcustomer` GROUP by Telephone)a where Count > 1)

//no of customer by province
select Province, COUNT(*) Count from (select telephone, Province from (SELECT DISTINCT telephone,Postcode FROM `postcustomer`)a LEFT JOIN Postcode on a.Postcode = Postcode.Postcode)b group by Province order by Count desc
