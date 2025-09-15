drop table if exists `states`;
create table `states` (`id` bigint unsigned not null auto_increment primary key, `city` varchar(255) not null, `state` varchar(255) not null, `created_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';

drop table if exists `users`;
create table `users` (`id` bigint unsigned not null auto_increment primary key, `business_name` varchar(255) null, `first_name` varchar(255) null, `last_name` varchar(255) null, `username` varchar(255) null, `email` varchar(255) not null, `phone` varchar(255) not null, `password` varchar(255) not null, `state` varchar(255) null, `city` varchar(255) null, `address` text null, `zip_code` varchar(255) null, `industry` varchar(255) null, `employee_id` varchar(255) null, `remember_token` varchar(255) null, `email_verified_at` timestamp null, `active` tinyint(1) not null default '1', `profile_picture` varchar(255) not null default 'default.png', `active_publisher` tinyint(1) not null default '0', `type` varchar(255) not null default 'user', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';

alter table `users` add `freelancer_id` int null;
create table `freelancers` (`id` bigint unsigned not null auto_increment primary key, `user_id` int not null, `photo` varchar(255) null, `photo_of_govt_id` varchar(255) null, `photo_with_govt_id` varchar(255) null, `bills` text null, `portfolio_website` varchar(255) null, `description` text null, `salary_requirements` varchar(255) null, `full_time` varchar(255) null, `hourly_rate` varchar(255) null, `skills_experience` text null, `skills_assessment` text null, `upwork` varchar(255) null, `fiverr` varchar(255) null, `linkedin` varchar(255) null, `instagram` varchar(255) null, `facebook` varchar(255) null, `youtube` varchar(255) null, `tiktok` varchar(255) null, `twitter` varchar(255) null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `freelancers` add `verification_level` varchar(255) not null default 'Verified';

drop table if exists `posts`;
create table `posts` (`id` bigint unsigned not null auto_increment primary key, `user_id` int not null, `title` varchar(255) null, `description` text null, `portfolio` varchar(255) null, `skills` text null, `upwork` varchar(255) null, `fiverr` varchar(255) null, `linkedin` varchar(255) null, `instagram` varchar(255) null, `facebook` varchar(255) null, `youtube` varchar(255) null, `tiktok` varchar(255) null, `twitter` varchar(255) null, `video` varchar(255) null, `thumbnail` varchar(255) null, `status` varchar(255) null, `status_description` text null, `active` tinyint(1) not null default '1', `is_featured` tinyint(1) not null default '0', `is_approved_by_admin` tinyint(1) not null default '0', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';

create table `customers` (`id` bigint unsigned not null auto_increment primary key, `billable_id` bigint unsigned not null, `billable_type` varchar(255) not null, `trial_ends_at` timestamp null, `created_at` timestamp null, `updated_at` timestamp null)  default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `customers` add index `customers_billable_id_billable_type_index`(`billable_id`, `billable_type`);
create table `subscriptions` (`id` bigint unsigned not null auto_increment primary key, `billable_id` bigint unsigned not null, `billable_type` varchar(255) not null, `name` varchar(255) not null, `paddle_id` int not null, `paddle_status` varchar(255) not null, `paddle_plan` int not null, `quantity` int not null, `trial_ends_at` timestamp null, `paused_from` timestamp null, `ends_at` timestamp null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `subscriptions` add index `subscriptions_billable_id_billable_type_index`(`billable_id`, `billable_type`);
alter table `subscriptions` add unique `subscriptions_paddle_id_unique`(`paddle_id`);
create table `receipts` (`id` bigint unsigned not null auto_increment primary key, `billable_id` bigint unsigned not null, `billable_type`varchar(255) not null, `paddle_subscription_id` bigint unsigned null, `checkout_id` varchar(255) not null, `order_id` varchar(255) not null, `amount` varchar(255) not null, `tax` varchar(255) not null, `currency` varchar(3) not null, `quantity` int not null, `receipt_url` varchar(255) not null, `paid_at` timestamp not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `receipts` add index `receipts_billable_id_billable_type_index`(`billable_id`, `billable_type`);
alter table `receipts` add index `receipts_paddle_subscription_id_index`(`paddle_subscription_id`);
alter table `receipts` add unique `receipts_order_id_unique`(`order_id`);
alter table `receipts` add unique `receipts_receipt_url_unique`(`receipt_url`);

create table `packages` (`id` bigint unsigned not null auto_increment primary key, `order` int(11) NOT NULL, `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, `tagline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, `paddle_id_monthly` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, `amount_monthly` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, `discounted_amount_monthly` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, `paddle_id_annually` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, `amount_annually` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, `discounted_amount_annually` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, `details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL, `highlighted_details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL, `active` tinyint(1) NOT NULL DEFAULT 1, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `packages` (`id`, `order`, `name`, `tagline`, `paddle_id_monthly`, `amount_monthly`, `discounted_amount_monthly`, `paddle_id_annually`, `amount_annually`, `discounted_amount_annually`, `details`, `highlighted_details`, `active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Default', 'Test subscription', '32388', '30', '', '32392', '300', NULL, '1\n2\n3', '4\n5\n6', 1, '2022-07-22 03:08:11', '2022-07-22 03:21:52');

alter table `freelancers` add `years_experience` varchar(255) null, add `date_of_birth` varchar(255) null, add `gender` varchar (255) null;
alter table `freelancers` add `verification_score` int not null default '0';
alter table `states` add `country` varchar(255) not null default 'America' after `id`;
alter table `freelancers` add `photo_of_govt_id_back` varchar(255) null after `photo_of_govt_id`;
alter table `freelancers` add `job_title` varchar(255) null;

create table `user_flags` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(255) not null, `description` varchar(255) not null, `active` tinyint(1) not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
create table `flaged_users` (`id` bigint unsigned not null auto_increment primary key, `flag_id` int unsigned not null, `reported_user_id` int unsigned not null, `user_id` int unsigned not null, `description` varchar(255) null, `action_taken` varchar(255) not null, `action description` varchar(255) not null, `resolved` tinyint(1) not null default '0', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `flaged_users` add index `flaged_users_flag_id_index`(`flag_id`);
alter table `flaged_users` add index `flaged_users_post_id_index`(`reported_user_id`);
alter table `flaged_users` add index `flaged_users_user_id_index`(`user_id`);

INSERT INTO `user_flags` VALUES (1,'Inappropriate','The user posts inappropriate content',1,NULL,NULL),(2,'Violence','The video posts violence videos',1,NULL,NULL),(3,'Abusive','The user posts abusive content',1,NULL,NULL),(4,'Scam','The user is a scam',1,NULL,NULL);

alter table `users` add `uuid` varchar(255) null;
alter table `users` add `deleted_at` datetime null;