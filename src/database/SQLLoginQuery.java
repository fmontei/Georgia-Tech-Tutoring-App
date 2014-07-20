package database;

import model.User;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

public class SQLLoginQuery {
    private Connection dbConnection = ConnectionManager.getConnection();

    public User getUserByGTID(final String gtid, final String password)
        throws SQLException {
        final String errorMessage = "User does not exist or authentication" +
                " failed. Please try again.";
        if (gtid == null || password == null) {
            throw new SQLException(errorMessage);
        }
        User user = getProfessorByGTID(gtid, password);
        if (user == null) user = getAdministratorByGTID(gtid, password);
        if (user == null) user = getTutorByGTID(gtid, password);
        if (user == null) user = getStudentByGTID(gtid, password);
        if (user == null) throw new SQLException(errorMessage);
        return user;
    }

    private User getProfessorByGTID(final String gtid, final String password) {
        User profUser = null;
        try {
            final String query = "(SELECT GTID, Password FROM Professor AS P " +
                    "WHERE P.GTID = ? AND P.Password = ?);";
            PreparedStatement preparedStatement = dbConnection.prepareStatement(query);
            preparedStatement.setString(1, gtid);
            preparedStatement.setString(2, password);
            ResultSet professors = preparedStatement.executeQuery();
            int count = 0;
            while (professors.next())
                count++;
            if (count > 0)
                profUser = new User(gtid, "professor");
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            return profUser;
        }
    }

    private User getAdministratorByGTID(final String gtid, final String password) {
        User adminUser = null;
        try {
            final String query = "(SELECT GTID, Password FROM Administrator AS A\n" +
                    "WHERE A.GTID = ? AND A.Password = ?);";
            PreparedStatement preparedStatement = dbConnection.prepareStatement(query);
            preparedStatement.setString(1, gtid);
            preparedStatement.setString(2, password);
            ResultSet professors = preparedStatement.executeQuery();
            int count = 0;
            while (professors.next())
                count++;
            if (count > 0)
                adminUser = new User(gtid, "admin");
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            return adminUser;
        }
    }

    private User getTutorByGTID(final String gtid, final String password) {
        User tutorUser = null;
        try {
            final String query = "(SELECT GTID, Password FROM Tutor AS T\n" +
                    "WHERE T.GTID = ? AND T.Password = ?);";
            PreparedStatement preparedStatement = dbConnection.prepareStatement(query);
            preparedStatement.setString(1, gtid);
            preparedStatement.setString(2, password);
            ResultSet professors = preparedStatement.executeQuery();
            int count = 0;
            while (professors.next())
                count++;
            if (count > 0)
                tutorUser = new User(gtid, "tutor");
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            return tutorUser;
        }
    }

    private User getStudentByGTID(final String gtid, final String password) {
        User studentUser = null;
        try {
            final String query = "(SELECT GTID, Password FROM Student AS S\n" +
                    "WHERE S.GTID = ? AND S.Password = ?);";
            PreparedStatement preparedStatement = dbConnection.prepareStatement(query);
            preparedStatement.setString(1, gtid);
            preparedStatement.setString(2, password);
            ResultSet professors = preparedStatement.executeQuery();
            int count = 0;
            while (professors.next())
                count++;
            if (count > 0)
                studentUser = new User(gtid, "student");
        } catch (SQLException ex) {
            ex.printStackTrace();
        } finally {
            return studentUser;
        }
    }
}
