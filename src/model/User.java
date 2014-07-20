package model;

public class User {
    private String gtid, type;

    public User(final String gtid, final String type) {
        this.type = type;
        this.gtid = gtid;
    }

    public String getType() { return type; }
    public String getGTID() { return gtid; }
}
