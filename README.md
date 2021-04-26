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


![entry](https://github.com/jet-/tinyacc/blob/master/images/entry.png)

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

![entry](https://github.com/jet-/tinyacc/blob/master/images/statement.png)

* Text search --> Search through all the transactions

* Statistics --> View the account ballance through months and years

* Expenses/Revenue --> To see an report with chart representation 

![entry](https://github.com/jet-/tinyacc/blob/master/images/expenses.png)

* Mortgage --> Calculation on your mortgage

### Setup Docker

1. Install `docker` and `docker-compose`:

    - https://docs.docker.com/get-docker/
    - https://docs.docker.com/compose/install/

2. Create `.docker_env` file in the folder `docker`

    ```
    touch docker/.docker_env
    ```

3. Enter the following content

    ```
    MYSQL_USER=tinyacc
    MYSQL_PASSWORD=password
    MYSQL_ROOT_PASSWORD=root
    MYSQL_DATABASE=acc_usd
    TINYACC_DB_HOSTNAME=tinyacc_db
    ```

    **Note:** If you make changes to the code, use `docker-compose up --build` to rebuild the project.


### Running using Docker

1. Start the application

    ```
    docker-compose up
    ```

2. Point your browser to http://localhost:8000/rep1.php?curr=usd

### Manage Docker app database

1. Start the application

    ```
    docker-compose up
    ```

2. Point your browser to http://localhost:8080/


#

Buy me a coffee/beer - BTC: 1L25rmhgM9yvJYcUsUkNJf49EfFjQYmCbt

#
