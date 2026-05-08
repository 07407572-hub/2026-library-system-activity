<?php
declare (strict_types = 1);

namespace App\Entity;

class Student
{
    private int $studentId;
    private String $studentName;

    public function __construct()
    {

    }

    public function getStudentId()
    {
        return $this->studentId;
    }

    public function getName()
    {
        return $this->studentName;
    }

    public function setStudentId($studentId)
    {
        $this->studentId = $studentId;
        
    }

    public function setName($studentName)
    {
        $this->studentName = $studentName;
    }
}


?>