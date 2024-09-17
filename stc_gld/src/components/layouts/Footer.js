export default function Footer(){
    return (
        <footer className="footer">
            <div className="container-fluid">
                <div className="copyright float-right">
                    &copy;
                    <script>
                        document.write(new Date().getFullYear());
                    </script>, made with <i className="material-icons">favorite</i> by GIS
                </div>
            </div>
        </footer>
    );
}