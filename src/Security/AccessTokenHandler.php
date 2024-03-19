<?php

namespace App\Security;

use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use App\Entity\User;

use DateTimeImmutable;
use Lcobucci\Clock\FrozenClock;    
use Lcobucci\JWT\JwtFacade;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint;

//use Firebase\JWT\JWT;
//use Firebase\JWT\Key;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        
    ) {
    }


    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        
     try {      
       
       //https://lcobucci-jwt.readthedocs.io/en/latest/quick-start/
        $key = 'bWljbGF2ZQ==jlkjgyuygtuiygfgccbvjvghccgfxvc';
        
        $keyencoded = InMemory::base64Encoded($key);
        
        $decoded = (new JwtFacade())->parse($accesstoken, new Constraint\SignedWith(new Sha256(), $keyencoded));
        
        
        //print_r('key '.$key);
        //print_r('access token '.$accessToken);
        //$keyencoded = base64_encode($key);
        //$decoded = JWT::decode($accessToken, new Key($key, 'HS256'));
        //print_r('access token '.$decoded);
        return new UserBadge($decoded->getName(), new User($decoded->getName(), $decoded->getRoles() )
        
    );
        
    } catch (InvalidArgumentException $e) {
            throw new BadCredentialsException('Invalid credentials.');
    } catch (DomainException $e) {
            throw new BadCredentialsException('Invalid credentials.');
    } catch (SignatureInvalidException $e) {
            throw new BadCredentialsException('Invalid credentials.');
    } catch (BeforeValidException $e) {
            throw new BadCredentialsException('Invalid credentials.');
    } catch (ExpiredException $e) {
            throw new BadCredentialsException('Invalid credentials.');
    } catch (UnexpectedValueException $e) {
            throw new BadCredentialsException('Invalid credentials.');
    }

 
  
        
        
    }
}
