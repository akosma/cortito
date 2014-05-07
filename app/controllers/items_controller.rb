    def shorten

        else



        respond_to do |format|
            format.html do
            end
            format.xml { render_for_api }
            format.js { render_for_api }
        end

    end

    private


    def is_already_shortened_url?(url)
        shortened_url_prefix = APP_CONFIG["excluded_url_shorteners"]

        shortened_url_prefix.each do |prefix|
            if url.starts_with?(prefix)
                return true
            end
        end
        return false
    end

