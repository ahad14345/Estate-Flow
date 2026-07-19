CREATE OR REPLACE PACKAGE BODY EstateFlow_Search_Pkg AS

    PROCEDURE filter_projects (
        p_search    IN  VARCHAR2,
        p_status    IN  VARCHAR2,
        p_results   OUT ref_cursor
    ) IS
        v_sql VARCHAR2(4000);
    BEGIN
        v_sql := 'SELECT * FROM projects WHERE 1=1';
        
        IF p_search IS NOT NULL THEN
            v_sql := v_sql || ' AND UPPER(name) LIKE UPPER(:search)';
        ELSE
            v_sql := v_sql || ' AND (1=1 OR :search IS NULL)';
        END IF;

        IF p_status IS NOT NULL THEN
            v_sql := v_sql || ' AND status = :status';
        ELSE
            v_sql := v_sql || ' AND (1=1 OR :status IS NULL)';
        END IF;
        
        v_sql := v_sql || ' ORDER BY created_at DESC';

        OPEN p_results FOR v_sql USING 
            CASE WHEN p_search IS NOT NULL THEN '%' || p_search || '%' ELSE NULL END,
            p_status;
    END filter_projects;

    PROCEDURE filter_properties (
        p_search    IN  VARCHAR2,
        p_type      IN  VARCHAR2,
        p_status    IN  VARCHAR2,
        p_results   OUT ref_cursor
    ) IS
        v_sql VARCHAR2(4000);
    BEGIN
        v_sql := 'SELECT p.*, pr.name as project_name FROM properties p 
                  JOIN projects pr ON p.project_id = pr.id WHERE 1=1';
        
        IF p_search IS NOT NULL THEN
            v_sql := v_sql || ' AND UPPER(p.title) LIKE UPPER(:search)';
        ELSE
            v_sql := v_sql || ' AND (1=1 OR :search IS NULL)';
        END IF;

        IF p_type IS NOT NULL THEN
            v_sql := v_sql || ' AND p.type = :type';
        ELSE
            v_sql := v_sql || ' AND (1=1 OR :type IS NULL)';
        END IF;

        IF p_status IS NOT NULL THEN
            v_sql := v_sql || ' AND p.status = :status';
        ELSE
            v_sql := v_sql || ' AND (1=1 OR :status IS NULL)';
        END IF;
        
        v_sql := v_sql || ' ORDER BY p.created_at DESC';

        OPEN p_results FOR v_sql USING 
            CASE WHEN p_search IS NOT NULL THEN '%' || p_search || '%' ELSE NULL END,
            p_type,
            p_status;
    END filter_properties;

END EstateFlow_Search_Pkg;
/