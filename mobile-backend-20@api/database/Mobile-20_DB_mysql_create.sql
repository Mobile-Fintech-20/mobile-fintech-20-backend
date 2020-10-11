-- CREATE DATABASE `db_mobile_20`;

CREATE TABLE `Phone` (
	`id` bigint NOT NULL AUTO_INCREMENT,
	`number` bigint NOT NULL ,
    `member_id` bigint NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `Designations` (
	`id` bigint NOT NULL AUTO_INCREMENT,
	`title` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
);

INSERT INTO `Designations`(`title`)VALUES
        ('Chairman'),
        ('Secretary'),
        ('Member');
        


CREATE TABLE `Member` (
	`member_id` bigint NOT NULL AUTO_INCREMENT,
	`first_name` varchar(255) NOT NULL,
    `last_name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
	`pin_no` varchar(255) ,
	`image` varchar(255),
	`Designation_ID` bigint NOT NULL,
	PRIMARY KEY (`member_id`)
);

CREATE TABLE `LoanType` (
	`loan_id` bigint NOT NULL AUTO_INCREMENT,
	`loan_type_name` varchar(255) NOT NULL,
	PRIMARY KEY (`loan_id`)
);
INSERT INTO `LoanType`(`loan_type_name`)VALUES
        ('Personal'),
        ('Business'),
        ('Others');



CREATE TABLE `LoanApplication` (
	`application_no` bigint NOT NULL AUTO_INCREMENT,
	`application_date` DATETIME NOT NULL DEFAULT current_timestamp(),
	`repayment_period` bigint NOT NULL DEFAULT 0,
	`approval_date` DATETIME  DEFAULT NULL,
	`interest` bigint DEFAULT 0,
	`member_id` bigint NOT NULL,
	`amount_applied` bigint NOT NULL,
	`amount_approved` bigint DEFAULT 0,
    `approve` BOOLEAN DEFAULT false,
	`loan_type_id` bigint NOT NULL,
	-- `loan_id` bigint NOT NULL,
	PRIMARY KEY (`application_no`)
);



CREATE TABLE `Loan` (
	`Loan_id` bigint NOT NULL AUTO_INCREMENT,
	`member_id` bigint NOT NULL,
	`deadline_payment_date` DATETIME NOT NULL,
	`amount_paid` int NOT NULL,
	`current_balance` int NOT NULL,
	`loan_disbursement_id` bigint NOT NULL,
	-- to add a field to contain loan status
	PRIMARY KEY (`Loan_id`)
);

CREATE TABLE `LoanDisbursement` (
	`loan_id` bigint NOT NULL AUTO_INCREMENT,
	`principal_amount` bigint NOT NULL ,
	`repayment_amount` bigint NOT NULL,
	`loan_app_id` bigint NOT NULL,
	PRIMARY KEY (`loan_id`)

);



CREATE TABLE `LoanRepayment` (
	`Loan_id` bigint NOT NULL AUTO_INCREMENT,
	`member_id` bigint NOT NULL,
	`repayment_amount` bigint NOT NULL,
	`repayment_date` Date NOT NULL,
	`balance_to_date` bigint NOT NULL,
	`payment_type_id` bigint NOT NULL,
	`loan_app_id` bigint NOT NULL,
	PRIMARY KEY (`Loan_id`)
);

CREATE TABLE `PaymentTypes` (
	`payment_id` bigint NOT NULL AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	PRIMARY KEY (`payment_id`)
);
INSERT INTO `PaymentTypes`(`name`)VALUES
        ('moblie money'),
        ('E banking'),
        ('Others');


CREATE TABLE `Guatantor` (
	`id` bigint NOT NULL AUTO_INCREMENT,
	`member_id` bigint NOT NULL,
	`loan_id` bigint NOT NULL,
	`Amount_guaranteed` bigint NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `Contribution` (
	`id` bigint NOT NULL AUTO_INCREMENT,
	`member_id` bigint NOT NULL,
	`amount_contributed` bigint NOT NULL,
	`contribution_date` DATETIME NOT NULL,
	`payment_type_id` bigint  NOT NULL,
	-- `receipt_no` DATETIME NOT NULL,
	-- `transaction_id` DATETIME NOT NULL,
	PRIMARY KEY (`id`)
);



CREATE TABLE `Rights` (
	`id` bigint NOT NULL AUTO_INCREMENT,
	`designation_id` bigint NOT NULL,
	`rights_value` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `Insurance` (
	`insurance_id` bigint NOT NULL AUTO_INCREMENT,
	`project_name` varchar(255) NOT NULL,
	`year` year NOT NULL,
	`member_id` bigint NOT NULL,
	PRIMARY KEY (`insurance_id`)
);

CREATE TABLE `ProjectAccident` (
	`id` bigint NOT NULL AUTO_INCREMENT,
	`amount` bigint NOT NULL,
	`project_accident_id` bigint NOT NULL,
	`insurance_no` bigint NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `projectAccidentReport` (
	`id` bigint NOT NULL AUTO_INCREMENT,
	`date` DATE NOT NULL,
	`location` varchar(255) NOT NULL,
	`recovery` BOOLEAN NOT NULL DEFAULT false,
	PRIMARY KEY (`id`)
);
-- USE  `db_anchors`;

ALTER TABLE `Phone` ADD CONSTRAINT `Phone_fk0` FOREIGN KEY (`member_id`) REFERENCES `Member`(`member_id`);

ALTER TABLE `Member` ADD CONSTRAINT `Member_fka` FOREIGN KEY (`Designation_ID`) REFERENCES `Designations`(`id`);

ALTER TABLE `LoanApplication` ADD CONSTRAINT `LoanApplication_fk0` FOREIGN KEY (`member_id`) REFERENCES `Member`(`member_Id`);

ALTER TABLE `LoanApplication` ADD CONSTRAINT `LoanApplication_fk1` FOREIGN KEY (`loan_type_id`) REFERENCES `LoanType`(`loan_id`);

ALTER TABLE `LoanDisbursement` ADD CONSTRAINT `LoanDiv_fk2` FOREIGN KEY (`loan_app_id`) REFERENCES `LoanApplication`(`application_no`);

ALTER TABLE `Loan` ADD CONSTRAINT `Loan_fk0` FOREIGN KEY (`loan_disbursement_id`) REFERENCES `LoanDisbursement`(`loan_id`);

ALTER TABLE `LoanRepayment` ADD CONSTRAINT `LoanRepayment_fk0` FOREIGN KEY (`loan_app_id`) REFERENCES `LoanDisbursement`(`loan_id`);

ALTER TABLE `LoanRepayment` ADD CONSTRAINT `LoanRepayment_fk1` FOREIGN KEY (`payment_type_id`) REFERENCES `PaymentTypes`(`payment_id`);

ALTER TABLE `Guatantor` ADD CONSTRAINT `Guatantor_fk0` FOREIGN KEY (`member_id`) REFERENCES `Member`(`member_Id`);

ALTER TABLE `Guatantor` ADD CONSTRAINT `Guatantor_fk1` FOREIGN KEY (`loan_id`) REFERENCES `LoanApplication`(`application_no`);

ALTER TABLE `Contribution` ADD CONSTRAINT `Contribution_fk0` FOREIGN KEY (`member_id`) REFERENCES `Member`(`member_Id`);

ALTER TABLE `Contribution` ADD CONSTRAINT `Contribution_fk1` FOREIGN KEY (`payment_type_id`) REFERENCES `PaymentTypes`(`payment_id`);

ALTER TABLE `Rights` ADD CONSTRAINT `Rights_fk0` FOREIGN KEY (`designation_id`) REFERENCES `Designations`(`id`);

ALTER TABLE `Insurance` ADD CONSTRAINT `Insurance_fk0` FOREIGN KEY (`member_id`) REFERENCES `Member`(`member_Id`);

ALTER TABLE `ProjectAccident` ADD CONSTRAINT `ProjectAccident_fk0` FOREIGN KEY (`project_accident_id`) REFERENCES `projectAccidentReport`(`id`);

ALTER TABLE `ProjectAccident` ADD CONSTRAINT `ProjectAccident_fk1` FOREIGN KEY (`insurance_no`) REFERENCES `Insurance`(`insurance_id`);

