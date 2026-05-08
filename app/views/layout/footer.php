        </main>
    </div>
    <?php $script_version = filemtime(__DIR__ . '/../../../public/assets/js/main.js'); ?>
    <script src="<?php echo wm_asset_url('js/main.js'); ?>?v=<?php echo $script_version; ?>"></script>
</body>
</html>
