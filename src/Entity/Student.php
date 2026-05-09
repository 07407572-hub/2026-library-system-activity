<?php
declare (strict_types = 1);

namespace App\Entity;

/**
 * Entity class representing a student in the library system
 *
 * @author Charles Kenneth Velasco
 * @since 1.0.0
 */
class Student
{
    private int $studentId;
    private String $studentName;

    /**
     * Constructor to initialize a Student entity
     */
    public function __construct()
    {

    }

    /**
     * Get the student ID
     *
     * @return int The student ID
     */
    public function getStudentId()
    {
        return $this->studentId;
    }

    /**
     * Get the student name
     *
     * @return String The student name
     */
    public function getName()
    {
        return $this->studentName;
    }

    /**
     * Set the student ID
     *
     * @param int $studentId The student ID
     */
    public function setStudentId($studentId)
    {
        $this->studentId = $studentId;

    }

    /**
     * Set the student name
     *
     * @param String $studentName The student name
     */
    public function setName($studentName)
    {
        $this->studentName = $studentName;
    }
}


?>