-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2025-02-03 02:21:50
-- サーバのバージョン： 10.4.32-MariaDB
-- PHP のバージョン: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `votematch`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `candidate_answer_tbl`
--

CREATE TABLE `candidate_answer_tbl` (
  `answer_id` int(16) NOT NULL COMMENT '候補者回答ID。自動採番',
  `election_id` int(10) NOT NULL COMMENT '選挙ID。外部キー',
  `candidate_id` int(10) NOT NULL COMMENT '候補者ID。外部キー',
  `question_number` int(2) NOT NULL COMMENT '質問番号。外部キー',
  `answer_value` int(2) NOT NULL COMMENT '回答順位',
  `answer_comment` text DEFAULT NULL COMMENT '回答コメント',
  `answer_note` text DEFAULT NULL COMMENT '管理用メモ。外向けには非表示',
  `last_updatetime` datetime NOT NULL COMMENT '最終更新日時',
  `create_datetime` datetime NOT NULL COMMENT '作成日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `candidate_answer_tbl`
--
-- 使いたければコメントアウトを削除
/*
INSERT INTO `candidate_answer_tbl` (`answer_id`, `election_id`, `candidate_id`, `question_number`, `answer_value`, `answer_comment`, `answer_note`, `last_updatetime`, `create_datetime`) VALUES
(21, 1, 1, 12, 1, 'テストコメント1', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(22, 1, 1, 7, 2, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(23, 1, 1, 3, 3, 'テストコメント3', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(24, 1, 1, 14, 4, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(25, 1, 1, 13, 5, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(26, 1, 1, 6, 6, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(27, 1, 1, 5, 7, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(28, 1, 1, 16, 8, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(29, 1, 1, 11, 9, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(30, 1, 1, 4, 10, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(31, 1, 1, 10, 11, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(32, 1, 1, 8, 12, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(33, 1, 1, 17, 13, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(34, 1, 1, 15, 14, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(35, 1, 1, 1, 15, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(36, 1, 1, 2, 16, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(37, 1, 1, 9, 17, 'テストコメント17', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(38, 1, 2, 9, 1, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(39, 1, 2, 17, 2, 'テストコメント２', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(40, 1, 2, 2, 3, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(41, 1, 2, 8, 4, 'テストコメント４', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(42, 1, 2, 4, 5, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(43, 1, 2, 5, 6, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(44, 1, 2, 12, 7, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(45, 1, 2, 1, 8, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(46, 1, 2, 7, 9, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(47, 1, 2, 6, 10, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(48, 1, 2, 16, 11, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(49, 1, 2, 13, 12, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(50, 1, 2, 11, 13, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(51, 1, 2, 15, 14, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(52, 1, 2, 3, 15, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(53, 1, 2, 10, 16, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(54, 1, 2, 14, 17, '', 'テスト用', '2025-02-01 00:00:00', '2025-02-01 00:00:00');
*/
-- --------------------------------------------------------

--
-- テーブルの構造 `candidate_tbl`
--

CREATE TABLE `candidate_tbl` (
  `candidate_id` int(16) NOT NULL COMMENT '候補者ID。自動採番',
  `election_id` int(10) NOT NULL COMMENT '選挙ID',
  `politician_id` int(10) NOT NULL COMMENT '政治家ID',
  `candidate_name` text NOT NULL COMMENT '候補者表示名',
  `candidate_information` text DEFAULT NULL COMMENT '候補者情報',
  `note` text DEFAULT NULL COMMENT '管理用メモ',
  `last_updatetime` datetime NOT NULL COMMENT '最終更新日時',
  `create_datetime` datetime NOT NULL COMMENT '作成日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `candidate_tbl`
--
-- 使いたければコメントアウトを削除
/*
INSERT INTO `candidate_tbl` (`candidate_id`, `election_id`, `politician_id`, `candidate_name`, `candidate_information`, `note`, `last_updatetime`, `create_datetime`) VALUES
(1, 1, 1, 'テスト　たろう', '事務所住所：\r\n〒XXXーXXXX\r\n大分市～\r\n\r\n電話番号：\r\n090-XXXX-XXXX\r\n\r\nメールアドレス：\r\ntest@Example.com\r\n\r\nウェブサイト：\r\nhttps://test.com/', '', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(2, 1, 2, 'テスト　はなこ', '', '', '2025-02-01 00:00:00', '2025-02-01 00:00:00');
*/
-- --------------------------------------------------------

--
-- テーブルの構造 `election_tbl`
--

CREATE TABLE `election_tbl` (
  `election_id` int(10) NOT NULL COMMENT '選挙ID。自動採番',
  `election_name` text NOT NULL COMMENT '選挙名',
  `start_date` date DEFAULT NULL COMMENT '告示日。決定前に入力する場合もあるためNULL許容。',
  `election_date` date DEFAULT NULL COMMENT '投開票日。決定前に入力する場合もあるためNULL許容。',
  `election_information` text DEFAULT NULL COMMENT '説明。リンクなども含められるように。',
  `election_note` text DEFAULT NULL COMMENT '管理用メモ。外向けには非表示',
  `last_updatetime` datetime NOT NULL COMMENT '最終更新日時',
  `create_datetime` datetime NOT NULL COMMENT '作成日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `election_tbl`
--
-- 使いたければコメントアウトを削除
/*
INSERT INTO `election_tbl` (`election_id`, `election_name`, `start_date`, `election_date`, `election_information`, `election_note`, `last_updatetime`, `create_datetime`) VALUES
(1, '2025年2月大分市議会議員選挙', '2025-02-09', '2025-02-16', '立候補予定者説明会\r\n日時：令和7年1月8日（水曜日）  午後1時30分（受付は午後1時から）\r\n\r\n場所：荷揚複合公共施設(別館)6階 多目的大会議室\r\n\r\n立候補の受付\r\n日時：令和7年2月9日（日曜日）  午前8時30分～午後5時\r\n\r\n場所：荷揚複合公共施設(別館)6階 多目的大会議室\r\n\r\n詳しくは\r\nhttps://www.city.oita.oita.jp/o234/sigisenrikkouhosetumeikai.html', NULL, '2024-12-21 06:39:32', '2024-12-21 06:39:32');
*/
-- --------------------------------------------------------

--
-- テーブルの構造 `politician_tbl`
--

CREATE TABLE `politician_tbl` (
  `politician_id` int(10) NOT NULL COMMENT '政治家ID。自動採番',
  `name` text NOT NULL COMMENT '氏名',
  `login_id` text DEFAULT NULL COMMENT 'ログインID',
  `login_password` text DEFAULT NULL COMMENT 'ログインパスワード',
  `birthday` date NOT NULL COMMENT '生年月日',
  `phone_number` text NOT NULL COMMENT '電話番号',
  `email_address` text NOT NULL COMMENT 'メールアドレス',
  `note` text DEFAULT NULL COMMENT '管理用メモ。外部非表示',
  `last_updatetime` datetime NOT NULL COMMENT '最終更新日時',
  `create_datetime` datetime NOT NULL COMMENT '作成日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `politician_tbl`
--
-- 使いたければコメントアウトを削除
/*
INSERT INTO `politician_tbl` (`politician_id`, `name`, `login_id`, `login_password`, `birthday`, `phone_number`, `email_address`, `note`, `last_updatetime`, `create_datetime`) VALUES
(1, 'テスト　太郎', '', '', '1983-04-01', '090-1111-1111', 'test@example.com', '', '2025-02-01 00:00:00', '2025-02-01 00:00:00'),
(2, 'テスト　花子', '', '', '1983-04-01', '090-1111-1111', 'test@example.com', '', '2025-02-01 00:00:00', '2025-02-01 00:00:00');
*/
-- --------------------------------------------------------

--
-- テーブルの構造 `questionnaire_tbl`
--

CREATE TABLE `questionnaire_tbl` (
  `questionnaire_id` int(15) NOT NULL COMMENT '質問ID。自動採番',
  `election_id` int(10) NOT NULL COMMENT '選挙ID。外部キー',
  `question_number` int(2) NOT NULL COMMENT '質問番号',
  `question_text` text NOT NULL COMMENT '質問内容',
  `question_note` text DEFAULT NULL COMMENT '管理用メモ。外向けには非表示',
  `last_updatetime` datetime NOT NULL COMMENT '最終更新日時',
  `create_datetime` datetime NOT NULL COMMENT '作成日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `questionnaire_tbl`
--
-- 使いたければコメントアウトを削除
/*
INSERT INTO `questionnaire_tbl` (`questionnaire_id`, `election_id`, `question_number`, `question_text`, `question_note`, `last_updatetime`, `create_datetime`) VALUES
(1, 1, 1, '医療・介護', NULL, '2024-12-21 07:04:23', '2024-12-21 07:04:23'),
(2, 1, 2, '子育て・教育', NULL, '2024-12-21 07:05:22', '2024-12-21 07:05:22'),
(3, 1, 3, '景気・雇用対策', NULL, '2024-12-21 07:05:38', '2024-12-21 07:05:38'),
(4, 1, 4, '高齢化対策', NULL, '2024-12-21 07:06:45', '2024-12-21 07:06:45'),
(5, 1, 5, '災害対策', NULL, '2024-12-21 07:07:04', '2024-12-21 07:07:04'),
(6, 1, 6, '原発・エネルギー', NULL, '2024-12-21 07:07:25', '2024-12-21 07:07:25'),
(7, 1, 7, '治安対策', NULL, '2024-12-21 07:07:59', '2024-12-21 07:07:59'),
(8, 1, 8, '地方議会の改革', NULL, '2024-12-21 07:08:19', '2024-12-21 07:08:19'),
(9, 1, 9, '地方自治のあり方', NULL, '2024-12-21 07:08:40', '2024-12-21 07:08:40'),
(10, 1, 10, '地方の行財政改革', NULL, '2024-12-21 07:08:56', '2024-12-21 07:08:56'),
(11, 1, 11, '中小企業対策', NULL, '2025-01-18 03:57:08', '2025-01-18 03:57:08'),
(12, 1, 12, '農林水産業の振興', NULL, '2025-01-18 03:57:51', '2025-01-18 03:57:51'),
(13, 1, 13, '社会資本整備', NULL, '2025-01-18 03:58:08', '2025-01-18 03:58:08'),
(14, 1, 14, '地域振興', NULL, '2025-01-18 03:58:20', '2025-01-18 03:58:20'),
(15, 1, 15, '男女共同参画／ジェンダー平等', NULL, '2025-01-18 03:58:35', '2025-01-18 03:58:35'),
(16, 1, 16, '外国人との共生', NULL, '2025-01-18 03:59:05', '2025-01-18 03:59:05'),
(17, 1, 17, '環境対策', NULL, '2025-01-18 03:59:17', '2025-01-18 03:59:17');
*/
--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `candidate_answer_tbl`
--
ALTER TABLE `candidate_answer_tbl`
  ADD PRIMARY KEY (`answer_id`);

--
-- テーブルのインデックス `candidate_tbl`
--
ALTER TABLE `candidate_tbl`
  ADD PRIMARY KEY (`candidate_id`),
  ADD UNIQUE KEY `election_id` (`election_id`,`politician_id`);

--
-- テーブルのインデックス `election_tbl`
--
ALTER TABLE `election_tbl`
  ADD PRIMARY KEY (`election_id`),
  ADD UNIQUE KEY `election_name` (`election_name`) USING HASH;

--
-- テーブルのインデックス `politician_tbl`
--
ALTER TABLE `politician_tbl`
  ADD PRIMARY KEY (`politician_id`),
  ADD UNIQUE KEY `name` (`name`,`birthday`) USING HASH;

--
-- テーブルのインデックス `questionnaire_tbl`
--
ALTER TABLE `questionnaire_tbl`
  ADD PRIMARY KEY (`questionnaire_id`),
  ADD UNIQUE KEY `election_id` (`election_id`,`question_number`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `candidate_answer_tbl`
--
ALTER TABLE `candidate_answer_tbl`
  ADD CONSTRAINT `candidate_id_foreignkey` FOREIGN KEY (`candidate_id`) REFERENCES `candidate_tbl` (`candidate_id`),
  ADD CONSTRAINT `election_id_foreignkey2` FOREIGN KEY (`election_id`) REFERENCES `election_tbl` (`election_id`);

--
-- テーブルの制約 `questionnaire_tbl`
--
ALTER TABLE `questionnaire_tbl`
  ADD CONSTRAINT `election_id_foreignkey` FOREIGN KEY (`election_id`) REFERENCES `election_tbl` (`election_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
