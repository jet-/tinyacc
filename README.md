# tinyacc
https://github.com/jet-/tinyacc

Tiny accounting app for basic tracking of personal finances. Running on LAMP stack

1. You have to create Database in MySQL/MariaDB server (for example acc_usd)

2. Create tables

	mysql -p acc_usd < mysql_tables.sql

3. Create user in MySQL/MariaDB and grant access for the database

4. Change the conf.php file accordingly

5. Copy all the files from the project to your web server 

	git clone https://github.com/jet-/tinyacc.git

6. Edit conf.php to reflect db access

7. Point the browser to https://server/tinyacc/rep1.php?curr=usd

#

* Add Document -> place an entry in the system

If "Accounted" is unchecked - the transaction is ignored in calculations


[[images/entry.png]]

Do an initial data entry to initialise the data - for example loading your bank accounts/cards and cash, I am doing it by: 

	cash -- Amount -- Other

Or 

	Checking account -- Amount -- Other

You can start adding your daily transactions as they happen. For example cash withdrawal at an ATM: 

	cash -- Amount -- Checking account

* Paying a bill: 

	Electrical bills -- Amount -- Checking account

* Depositing money in Savings account: 

	Savings account -- Amount -- Cash

Documents which are not accounted (don't have the flag "accounted") are ignored in calculations.

Balances are calculated dynamically and you can account or "un-account" or edit old documents at your will.

You can create more databases - for example for different currencies (I have one for bitcoins or I should say mini Bitcoins) .There are different reports and charts to have a better clue about you finances. Using the program you can also check when you bought something and whether warranty expired or not by searching by text or part of the text.


#

* General Ledger - to see the transactions for period (by default for the current month)

and at the bottom the current Balance


* Chk acc Dt=Kt  --> you should not have any of these.

* Acc Statement --> Can see the statement of an account

* Text search --> Search through all the transactions

* Statistics --> View the account ballance through months and years

* Expenses/Revenue --> To see an report with chart representation 

[[images/expenses.png]]

* Mortgage --> Calculation on your mortgage

#

If you want to buy me a coffee/beer - BTC: 1L25rmhgM9yvJYcUsUkNJf49EfFjQYmCbt

#
