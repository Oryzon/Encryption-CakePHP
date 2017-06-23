# Encryption-CakePHP

This component is free to use. 
With this component, you can encrypt some data with a salt, and decrypt them ONLY with the same salt. Allow, all data will be crypt differently.

By example, with the same salt, data like password will be encrypt everytime differently.

This component is usefull for creating a password gestionnary, or a secure chat.

This component works with CakePHP 3.x.

Very simple to use it.

In your controller, add :
  use App\Controller\Component\EncryptionComponent;

And for use it, juste write :
  $messageCrypt = EncryptionComponent::Crypt('Hello World', $your_salt);
  $messageDecrypt = EncryptionComponent::Decrypt($messageCrypt, $your_salt);

Very simple, very quick.
