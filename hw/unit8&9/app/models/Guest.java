package models;

import io.ebean.Model;

import play.data.format.Formats;
import play.data.validation.Constraints;

import javax.persistence.Id;
import javax.persistence.Entity;
import javax.persistence.Table;
import java.util.Date;

/**
 * guest entity managed by Ebean
 */
@Entity
@Table(name="guest_play")
public class Guest extends Model {

    private static final long serialVersionUID = 1L;

    @Id
    public Long guest_id;

    @Constraints.Required
    @Constraints.MaxLength(20)
    public String guest_name;

    @Constraints.Required
    @Constraints.Min(1)
    @Constraints.Max(120)
    public Integer age;

    @Constraints.Required
    public String gender;

    @Constraints.Required
    @Constraints.Email
    @Constraints.MaxLength(60)
    public String e_mail;
}

