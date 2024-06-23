<?php
namespace App\Infrastructure\Dao;
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Domain\Entity\UserAge;
use \PDO;
use \PDOException;

final class UserAgeDao
{
  const TABLE_NAME = 'users_age';
  private $pdo;

  public function __construct()
  {
    try {
      $this->pdo = new PDO(
        'mysql:dbname=blog;host=mysql;charset=utf8',
          'root',
          'password'
        );
    } catch (PDOException $e) {
      exit('DB接続エラー:'.$e->getMessage());
    }
  }

  public function create(UserAge $userAge): void
  {
    $sql = sprintf(
      'INSERT INTO %s (user_id, age) VALUES (:user_id, :age)',
      self::TABLE_NAME
    );
    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':user_id', $userAge->id()->value(), PDO::PARAM_STR
    );
    $statement->bindValue(':age', $userAge->age()->value(), PDO::PARAM_INT);
    $statement->execute();
  }

  public function fetchAll(int $userId): ?array
  {
    $sql = sprintf(
      'SELECT * FROM %s WHERE user_id = :user_id', self::TABLE_NAME
    );
    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':user_id', $userId, PDO::PARAM_STR);
    $statement->execute();
    $userAge = $statement->fetch(PDO::FETCH_ASSOC);

    return $userAge === false ? null : $userAge;
  }
}