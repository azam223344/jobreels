<?php

namespace App\Constants;

class Message
{
	// General Constants
	const PERMISSION_DENIED = 'You don\'t have access to this operation.';
	const INVALID_TOKEN = 'Invalid Token! User Not Found.';
	const SOMETHING_WENT_WRONG = 'Something went wrong. Please try again later!';
	const INVALID_INPUT_VALUES = 'Invalid input values.';
	const TOKEN_EXPIRED = 'Token Expired.';
	const TOKEN_NOT_FOUND = 'Token Not Found.';
	const REQUEST_SUCCESSFUL = 'Request Successful.';
	const INVALID_ID = 'Invalid ID.';
	const DATA_UPDATED_SUCCESSFULLY = "Data updated successfully.";
	const NOT_ADMIN = 'Only Admin Can Perform This Action.';
	const RECORD_NOT_FOUND = "Record Not Found.";
	const QR_CODE_NOT_LINKED = "QR Code not Linked to Any Order.";
	const NO_FORRUN_USER = 'No Forrun User Not Found.';
	const FORRUN_USER_FOUND = 'Forrun User Opened Successfully.';
	const SINGLE_SOLD_PRODUCT 	= "Single Sold Product Details.";
	const ADMIN_DASH 	= "Admin dashboard.";
	const QR_ADDED 	= "QR Code Added.";
	const RECIPT_FILTER_MESSAGE = "Undefined Filter.";
	const FEATURE_FILTER_MESSAGE = "Undefined Filter.";
	const FEATURE_RECIPT = "Payment Recipt";
	const NOT_ADMIN_NOT_FORRUN = "Only Admin and Forrun User Can Perform this Action.";
	const COMPLAIN_IMAGE_UPLOADED 	= "Image upload seccessfully.";
	const COMPLAIN_IMAGE_NOT_UPLOADED 	= "Image Not uploaded try again Later.";
	const FORRUN_DASH 	= "Forrun dashboard.";
	const NOT_FORRUN 	= "Only Forrun User Can Perform This Action.";
	const PRODUCT_UPLOADED = "Product Uploaded";
	const PRODUCT_UPDATED = "Product Updated";
	const CREATE_DUKAN = "Please Complete Your Dukan First to Add Product";
	const PRODUCT_ZERO_QUANTITY = "Product's size, quantity or colour is missing.";
	const NO_PRODUCT_IMAGE = "Upload at least 1 Image.";
	const UNAUTHORIZE = "UnAuthorize Access";
	const PRODUCT_WEIGHT_LESS_THEN_ZERO = "Estimated Weight of Product is To much It Should be 10kg or less then 10kg and greater then 0.";
	const LOW_WALLET_BALANCE = "Your Gahhak Wallet Balance Is Insufficent.";
	const ADD_ALL_TO_INDEX 	= "All User and Product data is Indexed on Elastic Search Server.";
	// Email Subject 

	const WELCOME_SUBJECT 	= "Welcome To Gahhak Team";
	const PASSWORD_FORGOT_SUBJECT = "Forget Password ";
	const OTP_SUBJECT = "OTP for Mobile Number Registration";
	const FUND_CREDITED_SUBJECT = "Funds Credited to Gahhak Wallet "; 
	const REPORT_PRODUCT_USER = "Product Report Email";
	const FUND_WITHDRAWAL_REQUEST = "Funds Withdrawal Request";
	const Withdrawal_REQUEST_REJECT = "Funds Withdrawal Request Reject";
	const ORDER_PLACE_SUBJECT ="Order Placed On Gahhak";
	const FLYER_REQUEST_APPROVED = "Flyer Request Approved";
	const FLYER_REQUEST_REJECTED = "Flyer Request Rejected";
	const AMOUNT_RECIEVED_FROM_FORRUN = "Amount Recieved From Forrun";
	const DELIVERY_CHARGES_EDITED = "Delivery Charges Updated Successfully.";
	const FLYER_FILTER_UNDEFINED = "Undefined Filter Or filter Not Found.";
	const LIST_FLYER = "List of Flyer Request.";
	const FEED_BACK_REPLY = "Feed Back Reply ";
	const PASSWORD_CHANGED_SUCCESSFULLY = 'Password changed successfully.';
	const PASSWORD_NOT_CHANGED = 'Password Not changed.';
	const CONTACT_NUMBER_TAKEN = 'Contact Number Already Taken.';
	const VARIANCE_ORDER_DETAILS = "Variance Order Details.";
	const INVALID_DELIVERY_DETAILS = "Please fill all Delivery details i.e Address, Name, cityId, contactNo.";
	const EMPTY_CART = "Your Cart is Empty.";
	const PRODUCT_DELETED = " product is deleted, Kindly remove it from cart.";
	const PRODUCT_OUT_OF_STOCK = " is out of stock now, Kindly remove it from cart.";
	const ORDER_PLACED_SUCCESSFULLY = "Your Order is Successfully placed For approval by Seller.";
	const SELLER_NOT_FOUND = "Your Order is Successfully placed For approval by Seller.";
	const ORDER_NOT_FOUND = "Order Not Found.";
	const ORDER_ALREADY_CANCEL = "Order Already Canceled.";
	const USER_BLOCK = 'Your Dukan has been Blocked By the Admin.';
	const WRONG_PRODUCT_OR_USER = "Wrong ProductId Or userId Sent.";
	const OWN_PRODUCT_TO_CART = "You Can't add your Own product To Cart.";
	const QUANTITY_LESSTHEN_ONE = "Quantity can't be less then 1.";
	const OUT_OF_STOCK = "Product is not Avilable or its Out Of Quantity.";
	const SIZE_OUT_OF_STOCK = "Product Size is Not Avilable or It's Out Of Quantity.";
	const PRODUCT_ADDED_TO_CART = "Product Added to your Cart.";
	const NOT_ENOUGH_QUANTITY = "Quantity Can't be Updated. Not Enough Quantity.";
};