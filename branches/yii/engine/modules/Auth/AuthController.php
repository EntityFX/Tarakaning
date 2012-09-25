<?php

Loader::LoadModuleController('AuthCheckerControllerAbstract'); 

class AuthController extends AuthCheckerControllerAbstract
{
	const DO_REGISTRATION_URL="/registration/";
}