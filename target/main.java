import database.SQLLoginQuery;
import database.SQLTutorSearchQuery;

import java.sql.SQLException;

public class main {
    public static void main(String... args) {
        try {
            SQLLoginQuery loginQuery = new SQLLoginQuery();
            loginQuery.getUserByGTID("000000020", "000000020");
            SQLTutorSearchQuery tutorSearchQuery = new SQLTutorSearchQuery();
            tutorSearchQuery.getAvailableStudentCourses("000000020");
        } catch (SQLException ex) {
            ex.printStackTrace();
        }
    }
}
