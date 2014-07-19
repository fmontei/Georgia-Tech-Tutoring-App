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

    public List<Course> getAvailableStudentCourses(final String gtid)
            throws SQLException {
        final String query = "SELECT Tutors.School, Tutors.Number\n" +
                "FROM Tutors, Tutor\n" +
                "WHERE Tutors.GTID_Tutor <> ?\n" +
                "GROUP BY Tutors.Number\n" +
                "ORDER BY Tutors.School";
        PreparedStatement preparedStatement = dbConnection.prepareStatement(query);
        preparedStatement.setString(1, gtid);
        ResultSet resultSet = preparedStatement.executeQuery();
        List<Course> courses = new ArrayList<Course>();
        while (resultSet.next()) {
            final String school = resultSet.getString("School");
            final int number = resultSet.getInt("Number");
            Course newCourse = new Course(school, number);
            courses.add(newCourse);
        }
        return courses;
    }
}
