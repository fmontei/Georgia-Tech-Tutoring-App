package database;

import model.Course;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class SQLTutorSearchQuery {
    private Connection dbConnection = ConnectionManager.getConnection();

    public List<Course> getAvailableStudentCourses(final String schoolName,
                                                   final int courseNumber,
                                                   final String[] preferredDays,
                                                   final String[] preferredTimes)
        throws SQLException {
        if (schoolName == null || preferredDays == null || preferredTimes == null)
            throw new SQLException("Database error: the values were invalid");
        PreparedStatement execute = prepareCourseSearchQuery(schoolName,
                courseNumber, preferredDays, preferredTimes);
        System.out.println(execute.toString());
        ResultSet courses = execute.executeQuery();
        List<Course> foundCourses = new ArrayList<Course>();
        while (courses.next()) {
            final String name = courses.getString("Name");
            final String email = courses.getString("Email");
            final double avgProfRating = courses.getDouble("Avg_Prof_Rating");
            final int numProf = courses.getInt("Num_Professors");
            final double avgStudentRating = courses.getDouble("Avg_Student_Rating");
            final int numStudents = courses.getInt("Num_Students");
            final Course course = new Course(name, email, avgProfRating,
                    avgStudentRating, numProf, numStudents);
            foundCourses.add(course);
            System.out.println(".\nName: " + name + "\nEmail: " + email
                    + "\nAvg Prof Rating: " + avgProfRating
                    + "\n#Professors: " + numProf
                    + "\nAvg Student Rating: " + avgStudentRating
                    + "\n#Students: " + numStudents + "\n\n");
        }
        return foundCourses;
    }

    private PreparedStatement prepareCourseSearchQuery(final String schoolName,
                                            final int courseNumber,
                                            final String[] preferredDays,
                                            final String[] preferredTimes)
        throws SQLException {
        StringBuilder queryBuilder = new StringBuilder();
        queryBuilder.append("SELECT DISTINCT Student.Name, Student.Email, " +
                "AVG(Recommends.Num_Evaluation) AS Avg_Prof_Rating, " +
                "COUNT(Recommends.Num_Evaluation) AS Num_Professors, " +
                "AVG(Rates.Num_Evaluation) AS Avg_Student_Rating, " +
                "COUNT(Rates.Num_Evaluation) AS Num_Students\n" +
                "FROM Student, Recommends, Rates, Tutors, Tutor_Time_Slot\n" +
                "WHERE Tutors.School = ? AND\n" +
                "Tutors.Number = ? AND\n" +
                "Tutor_Time_Slot.Semester = ? AND\n(");
        for (int i = 0; i < preferredDays.length; i++) {
            if (i <= preferredDays.length - 2)
                queryBuilder.append("(Tutor_Time_Slot.WeekDay = ? AND\n" +
                        "Tutor_Time_Slot.Time = ?) OR\n");
            else
                queryBuilder.append("(Tutor_Time_Slot.WeekDay = ? AND\n" +
                        "Tutor_Time_Slot.Time = ?)) AND\n");
        }
        queryBuilder.append("Tutor_Time_Slot.GTID = Student.GTID AND\n" +
                "Recommends.GTID_Tutor = Student.GTID AND\n" +
                "Rates.GTID_Tutor = Student.GTID AND\n" +
                "Student.GTID IN (SELECT DISTINCT Tutors.GTID_Tutor FROM Tutors)\n" +
                "GROUP BY Student.Email\n" +
                "ORDER BY Avg_Prof_Rating DESC;");
        final String query = queryBuilder.toString();
        final String currentSemester = "FALL";
        PreparedStatement preparedStatement = dbConnection.prepareStatement(query);
        preparedStatement.setString(1, schoolName);
        preparedStatement.setInt(2, courseNumber);
        preparedStatement.setString(3, currentSemester);
        int i = 4, j = 0;
        for ( ; i < (4 + preferredDays.length + preferredTimes.length); i++) {
            if (i % 2 == 0) preparedStatement.setString(i, preferredDays[j++]);
        }
        for (i = 5, j = 0; i < (5 + preferredDays.length + preferredTimes.length); i++) {
            if (i % 2 != 0) preparedStatement.setString(i, preferredTimes[j++]);
        }
        return preparedStatement;
    }

    public void prepareTutorsQuery(final String schoolName,
                                               final int courseNumber,
                                               final String[] preferredDays,
                                               final String[] preferredTimes)
            throws SQLException {
        StringBuilder queryBuilder = new StringBuilder();
        queryBuilder.append("SELECT Student.Name, Student.Email, " +
                "Tutor_Time_Slot.Weekday, Tutor_Time_Slot.Time\n" +
                "FROM Student, Recommends, Rates, Tutors, Tutor_Time_Slot\n" +
                "WHERE Tutors.School = ? AND\n" +
                "Tutors.Number = ? AND\n" +
                "Tutor_Time_Slot.Semester = ? AND\n(");
        for (int i = 0; i < preferredDays.length; i++) {
            if (i <= preferredDays.length - 2)
                queryBuilder.append("(Tutor_Time_Slot.WeekDay = ? AND\n" +
                        "Tutor_Time_Slot.Time = ?) OR\n");
            else
                queryBuilder.append("(Tutor_Time_Slot.WeekDay = ? AND\n" +
                        "Tutor_Time_Slot.Time = ?)) AND\n");
        }
        queryBuilder.append("Tutor_Time_Slot.GTID = Student.GTID AND\n" +
                "Recommends.GTID_Tutor = Student.GTID AND\n" +
                "Rates.GTID_Tutor = Student.GTID AND\n" +
                "Student.GTID IN (SELECT DISTINCT Tutors.GTID_Tutor FROM Tutors)\n" +
                "GROUP BY Tutor_Time_Slot.Time;");
        final String query = queryBuilder.toString();
        final String currentSemester = "FALL";
        PreparedStatement preparedStatement = dbConnection.prepareStatement(query);
        preparedStatement.setString(1, schoolName);
        preparedStatement.setInt(2, courseNumber);
        preparedStatement.setString(3, currentSemester);
        int i = 4, j = 0;
        for ( ; i < (4 + preferredDays.length + preferredTimes.length); i++) {
            if (i % 2 == 0) preparedStatement.setString(i, preferredDays[j++]);
        }
        for (i = 5, j = 0; i < (5 + preferredDays.length + preferredTimes.length); i++) {
            if (i % 2 != 0) preparedStatement.setString(i, preferredTimes[j++]);
        }
        System.out.println(preparedStatement.toString());
        final ResultSet tutors = preparedStatement.executeQuery();
        while (tutors.next()) {
            final String name = tutors.getString("Name");
            final String email = tutors.getString("Email");
            final String weekday = tutors.getString("Weekday");
            final String time = tutors.getString("Time");
            System.out.println(".\nName: " + name + "\nEmail: " + email
                    + "\nWeekday: " + weekday
                    + "\nTime: " + time + "\n\n");
        }
    }
}
