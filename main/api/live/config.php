<?php
;;;;;;;;;;;;;;;;;;;
; User Credentials ;
;;;;;;;;;;;;;;;;;;;

Payment.Username = "44DD7DxFUAvWa+H36Qiz37l1aX9dCi8VDw3DMKOTymr8qFvPY="
Payment.Password = "Aperture4"

;;;;;;;;;;;;;;;;;;;
; URL EndPoints ;
;;;;;;;;;;;;;;;;;;;

; This is the URL to the eWAY RapidAPI SOAP service
PaymentService.Soap = https://api.ewaypayments.com/soap.asmx

; This is the URL to the eWAY RapidAPI POST service
; Note: Change the .xml to .json if JSON format is used. e.g. https://api.ewaypayments.com/CreateAccessCode.json
PaymentService.POST.CreateAccessCode = https://api.ewaypayments.com/CreateAccessCode.xml
PaymentService.POST.GetAccessCodeResult = https://api.ewaypayments.com/GetAccessCodeResult.xml

; This is the URL to the eWAY RapidAPI REST service
PaymentService.REST = https://api.ewaypayments.com/AccessCode

; This is the URL to the eWAY RapidAPI RPC service
; Note: Change the json-rpc to xml-rpc if XML format is used. e.g. https://api.ewaypayments.com/xml-rpc
PaymentService.RPC = https://api.ewaypayments.com/json-rpc

; This is the URL to the eWAY RapidAPI via Ajax JSONP
PaymentService.JSONPScript = https://api.ewaypayments.com/JSONP/v1/js

;;;;;;;;;;;;;;;;;;;
; Method ;
;;;;;;;;;;;;;;;;;;;

;Method Options: SOAP,POST,REST,RPC
Request:Method = POST

;;;;;;;;;;;;;;;;;;;
; Message Format ;
;;;;;;;;;;;;;;;;;;;

;Format Options: JSON, XML
Request:Format = JSON

;;;;;;;;;;;;;;;;;;;
; Debug ;
;;;;;;;;;;;;;;;;;;;

; Set to 1 to see the response objects for CreateAccessCode & GetAccessCodeResult
; Also, it is able to see the raw response/request messages in either JSON or XML format being sent to the RapidAPI End Point.
ShowDebugInfo = 0

;;;;;;;;;;;;;;;;;;;
; Error Code and corresponding messages ;
;;;;;;;;;;;;;;;;;;;

F7000 = "Undefined Fraud"
V5000 = "Undefined System"
A0000 = "Undefined Approved"
A2000 = "Transaction Approved"
A2008 = "Honour With Identification"
A2010 = "Approved For Partial Amount"
A2011 = "Approved VIP"
A2016 = "Approved Update Track 3"
V6000 = "Undefined Validation"
V6001 = "Invalid Request CustomerIP"
V6002 = "Invalid Request DeviceID"
V6011 = "Invalid Payment Amount"
V6012 = "Invalid Payment InvoiceDescription"
V6013 = "Invalid Payment InvoiceNumber"
V6014 = "Invalid Payment InvoiceReference"
V6015 = "Invalid Payment CurrencyCode"
V6016 = "Payment Required"
V6017 = "Payment CurrencyCode Required"
V6018 = "Unknown Payment CurrencyCode"
V6021 = "Cardholder Name Required"
V6022 = "Card Number Required"
V6023 = "CVN Required"
V6031 = "Invalid Card Number"
V6032 = "Invalid CVN"
V6033 = "Invalid Expiry Date"
V6034 = "Invalid Issue Number"
V6035 = "Invalid Start Date"
V6036 = "Invalid Month"
V6037 = "Invalid Year"
V6040 = "Invaild Token Customer Id"
V6041 = "Customer Required"
V6042 = "Customer First Name Required"
V6043 = "Customer Last Name Required"
V6044 = "Customer Country Code Required"
V6045 = "Customer Title Required"
V6046 = "Token Customer ID Required"
V6047 = "RedirectURL Required"
V6051 = "Invalid Customer First Name"
V6052 = "Invalid Customer Last Name"
V6053 = "Invalid Customer Country Code"
V6054 = "Invalid Customer Email"
V6055 = "Invalid Customer Phone"
V6056 = "Invalid Customer Mobile"
V6057 = "Invalid Customer Fax"
V6058 = "Invalid Customer Title"
V6059 = "Redirect URL Invalid"
V6060 = "Redirect URL Invalid"
V6061 = "Invaild Customer Reference"
V6062 = "Invaild Customer CompanyName"
V6063 = "Invaild Customer JobDescription"
V6064 = "Invaild Customer Street1"
V6065 = "Invaild Customer Street2"
V6066 = "Invaild Customer City"
V6067 = "Invaild Customer State"
V6068 = "Invaild Customer Postalcode"
V6069 = "Invaild Customer Email"
V6070 = "Invaild Customer Phone"
V6071 = "Invaild Customer Mobile"
V6072 = "Invaild Customer Comments"
V6073 = "Invaild Customer Fax"
V6074 = "Invaild Customer Url"
V6075 = "Invaild ShippingAddress FirstName"
V6076 = "Invaild ShippingAddress LastName"
V6077 = "Invaild ShippingAddress Street1"
V6078 = "Invaild ShippingAddress Street2"
V6079 = "Invaild ShippingAddress City"
V6080 = "Invaild ShippingAddress State"
V6081 = "Invaild ShippingAddress PostalCode"
V6082 = "Invaild ShippingAddress Email"
V6083 = "Invaild ShippingAddress Phone"
V6084 = "Invaild ShippingAddress Country"
V6091 = "Unknown Country Code"
V6100 = "Invalid ProcessRequest name"
V6101 = "Invalid ProcessRequest ExpiryMonth"
V6102 = "Invalid ProcessRequest ExpiryYear"
V6103 = "Invalid ProcessRequest StartMonth"
V6104 = "Invalid ProcessRequest StartYear"
V6105 = "Invalid ProcessRequest IssueNumber"
V6106 = "Invalid ProcessRequest CVN"
V6107 = "Invalid ProcessRequest AccessCode"
V6108 = "Invalid ProcessRequest CustomerHostAddress"
V6109 = "Invalid ProcessRequest UserAgent"
V6110 = "Invalid ProcessRequest Number"
D4401 = "Refer to Issuer"
D4402 = "Refer to Issuer, special"
D4403 = "No Merchant"
D4404 = "Pick Up Card"
D4405 = "Do Not Honour"
D4406 = "Error"
D4407 = "Pick Up Card, Special"
D4409 = "Request In Progress"
D4412 = "Invalid Transaction"
D4413 = "Invalid Amount"
D4414 = "Invalid Card Number"
D4415 = "No Issuer"
D4419 = "Re-enter Last Transaction"
D4421 = "No Method Taken"
D4422 = "Suspected Malfunction"
D4423 = "Unacceptable Transaction Fee"
D4425 = "Unable to Locate Record On File"
D4430 = "Format Error"
D4431 = "Bank Not Supported By Switch"
D4433 = "Expired Card, Capture"
D4434 = "Suspected Fraud, Retain Card"
D4435 = "Card Acceptor, Contact Acquirer, Retain Card"
D4436 = "Restricted Card, Retain Card"
D4437 = "Contact Acquirer Security Department, Retain Card"
D4438 = "PIN Tries Exceeded, Capture"
D4439 = "No Credit Account"
D4440 = "Function Not Supported"
D4441 = "Lost Card"
D4442 = "No Universal Account"
D4443 = "Stolen Card"
D4444 = "No Investment Account"
D4451 = "Insufficient Funds"
D4452 = "No Cheque Account"
D4453 = "No Savings Account"
D4454 = "Expired Card"
D4455 = "Incorrect PIN"
D4456 = "No Card Record"
D4457 = "Function Not Permitted to Cardholder"
D4458 = "Function Not Permitted to Terminal"
D4460 = "Acceptor Contact Acquirer"
D4461 = "Exceeds Withdrawal Limit"
D4462 = "Restricted Card"
D4463 = "Security Violation"
D4464 = "Original Amount Incorrect"
D4466 = "Acceptor Contact Acquirer, Security"
D4467 = "Capture Card"
D4475 = "PIN Tries Exceeded"
D4482 = "CVV Validation Error"
D4490 = "Cutoff In Progress"
D4491 = "Card Issuer Unavailable"
D4492 = "Unable To Route Transaction"
D4493 = "Cannot Complete, Violation Of The Law"
D4494 = "Duplicate Transaction"
D4496 = "System Error"
?>