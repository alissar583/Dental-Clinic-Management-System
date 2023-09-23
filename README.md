# Dental Clinics management system Back-end


### 4th year university project

## Introduction
The system contains 4 actors : admin , doctor , secretary , patient with different roles and permissions .
send WhatsApp notification to patients before reservations, to doctor when the amount of certain medical product runs out or expires .
manage appointments, medical products ( generate QR code to product and Excel files for it's remaining amount ), patient records (creating pdf file to his general medical report and specialize part to his reservation in certain clinic)
<hr> 

Laravel 10, PHP 8.1, MySQL Database

## [Postman Collection](https://documenter.getpostman.com/view/20750849/2s93m7W1i1)

<hr> 

### Here is a list of the packages installed:
- jwt-auth.
- dompdf.
- maatwebsite excel.
- simple-qrcode.
- twilio/sdk.
- spatie permission.
- spatie medialibrary.


# Getting started
### Installation
<hr> 


- Clone this repository.
```
git clone https://github.com/alissar583/Dental-Clinic-Management-System.git

- copy this command to terminal for install the composer.
```
composer install
```
- copy this command for generate <code>.env</code> file .
```
cp .env.example .env 
```
- run this commands .
``` 
php artisan migrate

php artisan DB:seed

php artisan key:generate

php artisan storage:link
```

- Start the local server.
```
php artisan serve 
```


Notes:
- please add twilio configuration in the .env file

<hr>

## Now You Can Use This App 


