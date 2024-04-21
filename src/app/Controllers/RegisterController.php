<?php

namespace Ira\Controllers;

use Aruka\Controller\AbstractController;
use Aruka\Http\Response;
use Ira\Forms\User\RegisterForm;

class RegisterController extends AbstractController
{
    public function form(): Response
    {
        return $this->render('register.html.twig');
    }

    public function register()
    {
        // 1. Создает модель формы, которая будет:
        $form = new RegisterForm();

        $form->setFields(
            $this->request->input('email'),
            $this->request->input('password'),
            $this->request->input('password_confirmation'),
            $this->request->input('name'),
        );

        dd($form);

        // 2. Валидация
        // Если есть ошибки валидации, добавить в сессию и перенаправить на форму

        // 3. Зарегистрировать пользователя, вызвав $form->save()

        // 4. Добавить сообщение об успешной регистрации

        // 5. Войти в систему под пользователем

        // 6. Перенаправить на нужную страницу
    }
}
