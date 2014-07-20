package database;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class ConnectionManager {
    private static ConnectionManager instance = new ConnectionManager();
    public static final String URL = "jdbc:mysql://localhost:3306/cs_4400_project_db2";
    public static final String USER = "root";
    public static final String PASSWORD = "password";
    public static final String DRIVER_CLASS = "com.mysql.jdbc.Driver";

    private ConnectionManager() {
        try {
            Class.forName(DRIVER_CLASS);
        } catch (ClassNotFoundException e) {
            e.printStackTrace();
        }
    }

    public Connection createConnection() {
        Connection connection = null;
        try {
            connection = DriverManager.getConnection(URL, USER, PASSWORD);
        } catch (SQLException e) {
            System.out.println("ERROR: Unable to Connect to Database.");
        }
        return connection;
    }

    public static Connection getConnection() {
        return instance.createConnection();
    }
}
