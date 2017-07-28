Encryption Component, with CakePHP 3.
===================


Welcome. This is a Component for CakePHP 3, which allow you to create some wonderfull things.

----------


What's for ?
-------------

This component allow you to **crypt** some data, and **decrypt** them, with the **same salt value**.

You can use it for a lot of creation.

> **Like what ?**

> - You want to create a chat?
> - You want to create a password gestionnary ?
> - You want to create a file exchanger ?

**Easy.**

This component will crypt a data (like a password, or a string message), and when you use the same salt value, you can easily decrypt the data.
Also, if you wan to crypt the same data, with the same salt value, twice, you will have to different crypted data.

This component can be very usefull if you have the idea and a project which need this things, so, use it. :)

----------


How can I use it ?
-------------------

Copy the project in your project.

In the head of your controller, add :

    use App\Controller\Component\EncryptionComponent;

For crypt data, just use :

    $messageCrypt = EncryptionComponent::Crypt('Hello World', $your_salt);

For decrypt data, just use :

    $messageDecrypt = EncryptionComponent::Decrypt($messageCrypt, $your_salt);

And 'voila'. Done !

Have fun to use my creation for make your creation most powerfull !
