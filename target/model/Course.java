package model;

public class Course {
    private String firstName, lastName, email;
    double avgProfRating, avgStudentRating;
    int numProf, numStudent;

    public Course(final String name, final String email,
                  final double avgProfRating, final double avgStudentRating,
                  final int numProf, final int numStudent) {
        parseFirstAndLastNames(name);
        this.email = email;
        this.avgProfRating = avgProfRating;
        this.avgStudentRating = avgStudentRating;
        this.numProf = numProf;
        this.numStudent = numStudent;
    }

    private void parseFirstAndLastNames(final String name) {
        final int beginIndex = name.indexOf(" ");
        if (!name.contains(" ")) { firstName = name; lastName = ""; }
        firstName = name.substring(0, beginIndex);
        lastName = name.substring(beginIndex + 1);
    }

    public String getFirstName() { return firstName; }
    public String getLastName() { return lastName; }
    public String getEmail() { return email; }
    public double getAvgProfRating() { return avgProfRating; }
    public double getAvgStudentRating() { return avgStudentRating; }
    public int getNumProf() { return numProf; }
    public int getNumStudent() { return numStudent; }
}
