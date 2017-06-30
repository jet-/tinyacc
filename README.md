# tinyacc
https://github.com/jet-/tinyacc

Tiny accounting app for basic tracking of personal finances. Running on LAMP stack

1. You have to create Database in MySQL server (for example acc_usd)

2. Create tables

	mysql -p acc_usd < mysql_tables.sql

3. Create user in MySql and grant access for the database

4. Set username/ password in conf.php file

5. Copy all the files from the project to your web server 

6. Point the browser to https://server/acc_folder/rep1.php?curr=usd

===========


* Add Document -> place an entry in the system

If "Accounted" is unchecked - the transaction is ignored in calculations



Examples:

withdrawal from your checking account:

Cash On Hand  <--   100  <--   Checking Account  --     Cash Witdrawal Bank Of the Banks

---

Initial setup of checking account:

Checking Account <-- 1500 <-- Other   --   Open Checking account in bank of the Banks

---------------


* General Ledger - to see the transactions for period (by default for the current month)

and at the bottom the current Balance


* Chk acc Dt=Kt  --> you should not have any of these.

* Acc Statement --> Can see the statement of an account

* Text search --> Search through all the transactions

* Statistics --> View the account ballance through months and years

* Mortgage --> Calculation on your mortgage

---
