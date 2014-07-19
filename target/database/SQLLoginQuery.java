package database;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

public class SQLLoginQuery {
    private Connection dbConnection = ConnectionManager.getConnection();

    public void getUserByGTID(final String gtid, final String password)
        throws SQLException {
        final String errorMessage = "User does not exist or authentication" +
                " failed. Please try again.";
        if (gtid == null || password == null) {
            throw new SQLException(errorMessage);
        }
        final String query = "(SELECT GTID, Password FROM Administrator AS A " +
                "WHERE A.GTID = ? AND A.Password = ?) " +
                "UNION " +
                "(SELECT GTID, Password FROM Student AS S " +
                "WHERE S.GTID = ? AND S.Password = ?) " +
                "UNION " +
                "(SELECT GTID, Password FROM Professor AS P " +
                "WHERE P.GTID = ? AND P.Password = ?);";
        PreparedStatement preparedStatement = dbConnection.prepareStatement(query);
        preparedStatement.setString(1, gtid);
        preparedStatement.setString(2, password);
        preparedStatement.setString(3, gtid);
        preparedStatement.setString(4, password);
        preparedStatement.setString(5, gtid);
        preparedStatement.setString(6, password);
        ResultSet users = preparedStatement.executeQuery();
        int count = 0;
        while (users.next()) count++;
        if (count == 0) throw new SQLException(errorMessage);
    }
}
