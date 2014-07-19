package model;

public class Course {
    private String school;
    private int number;

    public Course(final String school, final int number) {
        this.school = school;
        this.number = number;
    }

    public String getSchool() {
        return school;
    }

    public int getNumber() {
        return number;
    }
}
