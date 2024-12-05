@extends('layouts.cms')

@section('content')
    <div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;  padding: 20px; border: 1px solid #ddd; border-radius: 8px; max-width: 600px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h1 style="color: #007bff; font-size: 24px; margin-bottom: 5px;">Подтверждение регистрации компании</h1>
            <p style="font-size: 14px; color: #666;">ООО "АЛГА"</p>
        </div>
        <p>Здравствуйте,</p>
        <p>Вы зарегистрировали компанию <strong>{{ $account->getCompany()->getName() }}</strong> с аккаунтом ID: <strong>{{ $account->getEmail() }}</strong>.</p>
        <p>Для завершения регистрации, пожалуйста, подтвердите ваш email, перейдя по следующей ссылке:</p>
        <div style="text-align: center; margin: 30px 0;">

            <a href="{{env("FRONT_URL")."/regok/?key=$confirmationToken"}}"
               style="background-color: #007bff; color: #fff; padding: 12px 25px; font-size: 16px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Подтвердить email
            </a>
        </div>
        <p>Если вы не регистрировались на нашем сайте, просто проигнорируйте это письмо.</p>
        <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">
        <p style="font-size: 12px; color: #999; text-align: center;">
            Это письмо создано автоматически, отвечать на него не нужно.<br>
        </p>
    </div>
@endsection
