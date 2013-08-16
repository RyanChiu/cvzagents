CREATE VIEW `view_stats` AS 
select `a`.`trxtime` AS `trxtime`,`b`.`companyid` AS `companyid`,`c`.`officename` AS `officename`,
	`a`.`agentid` AS `agentid`,`d`.`username` AS `username`,`d`.`username4m` AS `username4m`,
	`d`.`status` AS `status`,`a`.`siteid` AS `siteid`,`e`.`sitename` AS `sitename`,
	`a`.`typeid` AS `typeid`,`f`.`typename` AS `typename`,`g`.`price` AS `price`,
	`g`.`earning` AS `earning`,`a`.`raws` AS `raws`,`a`.`uniques` AS `uniques`,
	`a`.`chargebacks` AS `chargebacks`,`a`.`signups` AS `signups`,`a`.`frauds` AS `frauds`,
	`a`.`sales_number` AS `sales_number`,(`a`.`sales_number` - `a`.`chargebacks`) AS `net`,
	((`a`.`sales_number` - `a`.`chargebacks`) * `h`.`ownprice`) AS `payouts`,
	((`a`.`sales_number` - `a`.`chargebacks`) * `g`.`earning`) AS `earnings` 
from (((((((`stats` `a` join `agents` `b`) join `companies` `c`) join `accounts` `d`) 
	join `sites` `e`) join `types` `f`) join `fees` `g`) join `tmp_com_fees` `h`) 
where ((`a`.`agentid` = `b`.`id`) and (`b`.`companyid` = `c`.`id`) and (`a`.`agentid` = `d`.`id`) 
	and (`a`.`siteid` = `e`.`id`) and (`a`.`typeid` = `f`.`id`) and (`f`.`id` = `g`.`typeid`) 
	and (`a`.`trxtime` >= `g`.`start`) and (`a`.`trxtime` <= `g`.`end`) 
	and (`g`.`id` = `h`.`feeid`) and (`c`.`id` = `h`.`companyid`));