<?php
declare(strict_types=1);

namespace Alexx\Blog\Setup;

use Alexx\Blog\Model\BlogPosts;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Module InstallSchema
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Install method
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $table = $setup->getConnection()
            ->newTable($setup->getTable(BlogPosts::BLOG_TABLE))
            ->addColumn(
                BlogPosts::BLOG_ID,
                Table::TYPE_INTEGER,
                11,
                ['identity' => true, 'unsigned' => true, 'nullable' => false,
                    'primary' => true],
                'Entity ID'
            )->addColumn(
                'theme',
                Table::TYPE_TEXT,
                512,
                [],
                'Theme'
            )->addColumn(
                'content',
                Table::TYPE_TEXT,
                null,
                [],
                'Content'
            )->addColumn(
                'picture',
                Table::TYPE_TEXT,
                512,
                [],
                'Picture'
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Created At'
            )->addColumn(
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                'Updated At'
            );

        $setup->getConnection()->createTable($table);

        $setup->getConnection()->query("ALTER TABLE `" . BlogPosts::BLOG_TABLE . "` modify `theme` varchar (512)");
        $setup->getConnection()->query("ALTER TABLE `" . BlogPosts::BLOG_TABLE . "` modify `picture` varchar (512)");

        $setup->getConnection()->addIndex(
            $setup->getTable(BlogPosts::BLOG_TABLE),
            'theme_index',
            'theme',
            AdapterInterface::INDEX_TYPE_FULLTEXT
        );

        $setup->endSetup();
    }
}
